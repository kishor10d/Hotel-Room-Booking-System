<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

use App\Libraries\BaseController;

/**
 * Class : Roles (RolesController)
 * Roles Class to view roles and edit their per-module access matrix.
 * System Administrator only.
 * @author : DigiLodge
 * @version : 1.0
 * @since : 31 July 2026
 */
class Roles extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_model', 'rm');
        $this->isLoggedIn();
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('roleListing');
    }

    /**
     * This function is used to load the roles list
     */
    function roleListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->rm->roleListingCount($searchText);

            $returns = $this->paginationCompress ( "roleListing/", $count, 10 );

            $data['roleRecords'] = $this->rm->roleListing($searchText, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'DigiLodge : Roles Listing';

            $this->loadViews("roles/roleListing", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new role form
     */
    function add()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = 'DigiLodge : Add New Role';

            $this->loadViews("roles/add", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to check whether a role name already exists or not (AJAX)
     */
    function checkRoleExists()
    {
        $roleId = $this->input->post("roleId");
        $role = $this->input->post("role");

        $result = $this->rm->checkRoleNameExists($role, empty($roleId) ? 0 : $roleId);

        if(empty($result)) { echo("true"); }
        else { echo("false"); }
    }

    /**
     * This function is used to add a new role to the system
     */
    function addNewRole()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('role','Role Text','trim|required|max_length[50]');
            $this->form_validation->set_rules('status','Status','trim|required|numeric');

            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
                $roleText = $this->security->xss_clean($this->input->post('role'));
                $status = $this->security->xss_clean($this->input->post('status'));

                if(!empty($this->rm->checkRoleNameExists($roleText)))
                {
                    $this->session->set_flashdata('error', 'A role with this name already exists');
                    redirect('roleListing/add');
                }

                $roleInfo = array('role'=>$roleText, 'status'=>$status, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));

                $result = $this->rm->addNewRole($roleInfo);

                if($result > 0)
                {
                    $this->addRoleMatrix($result);
                    $this->session->set_flashdata('success', 'New Role created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Role creation failed');
                }

                redirect('roleListing');
            }
        }
    }

    /**
     * This function is used to load a role's edit form (role details + access matrix)
     * @param number $roleId : This is role id
     */
    function edit($roleId = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            if($roleId == null)
            {
                redirect('roleListing');
            }

            $data['roleInfo'] = $this->rm->getRoleInfo($roleId);
            if(empty($data['roleInfo']))
            {
                redirect('roleListing');
            }

            $roleAccessMatrix = $this->rm->getRoleAccessMatrix($roleId);
            $data['roleAccessMatrix'] = json_decode($roleAccessMatrix->access);

            $this->load->config('modules');
            $data['moduleList'] = $this->config->item('moduleList');

            $this->global['pageTitle'] = 'DigiLodge : Edit Role';

            $this->loadViews("roles/edit", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to update a role's text/status
     */
    function editRole()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');

            $roleId = $this->input->post('roleId');

            $this->form_validation->set_rules('role','Role Text','trim|required|max_length[50]');
            $this->form_validation->set_rules('status','Status','trim|required|numeric');

            if($this->form_validation->run() == FALSE)
            {
                $this->edit($roleId);
            }
            else
            {
                if($roleId == ROLE_ADMIN)
                {
                    $this->session->set_flashdata('error', 'The System Administrator role\'s name and status cannot be changed');
                    redirect('roleListing/edit/'.$roleId);
                }

                $roleText = $this->security->xss_clean($this->input->post('role'));
                $status = $this->security->xss_clean($this->input->post('status'));

                if(!empty($this->rm->checkRoleNameExists($roleText, $roleId)))
                {
                    $this->session->set_flashdata('error', 'A role with this name already exists');
                    redirect('roleListing/edit/'.$roleId);
                }

                $roleInfo = array('role'=>$roleText, 'status'=>$status, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));

                $result = $this->rm->editRole($roleInfo, $roleId);

                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Role updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Role updation failed');
                }

                redirect('roleListing/edit/'.$roleId);
            }
        }
    }

    /**
     * This function is used to soft-delete a role.
     * Blocked for the System Administrator role and for any role
     * currently assigned to an active user, to avoid locking users out.
     */
    function deleteRole()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
            return;
        }

        $roleId = $this->input->post('roleId');

        if($roleId == ROLE_ADMIN)
        {
            echo(json_encode(array('status'=>FALSE, 'message'=>'The System Administrator role cannot be deleted')));
            return;
        }

        if($this->rm->countUsersInRole($roleId) > 0)
        {
            echo(json_encode(array('status'=>FALSE, 'message'=>'This role is still assigned to one or more users and cannot be deleted')));
            return;
        }

        $roleInfo = array('isDeleted'=>1, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
        $result = $this->rm->deleteRole($roleId, $roleInfo);

        if($result > 0) {
            $this->rm->updateAccessMatrix($roleId, array('isDeleted'=>1, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s')));
            echo(json_encode(array('status'=>TRUE, 'message'=>'Role deleted successfully')));
        } else {
            echo(json_encode(array('status'=>FALSE, 'message'=>'Role deletion failed')));
        }
    }

    /**
     * This method used to build access matrix for a newly created role from
     * configuration and insert a default (all-zero) entry into the database
     * @param number $roleId : This is role id
     */
    private function addRoleMatrix($roleId)
    {
        $this->load->config('modules');
        $modules = $this->config->item('moduleList');

        $accessMatrix = array('roleId'=>$roleId, 'access'=>json_encode($modules), 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));

        $this->rm->insertAccessMatrix($accessMatrix);
    }

    /**
     * This method used to update the access rights for the role
     */
    public function storeAccessMatrix()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $roleId = $this->input->post('roleIdForMatrix');
            $postParams = $this->input->post('access');

            $this->load->config('modules');
            $modules = $this->config->item('moduleList');
            $modules2 = [];

            foreach($modules as $module) {
                $singleModule = ['module'=>$module['module']];
                foreach($module as $keyMod=>$valMod) {
                    if($keyMod == 'module') {
                        continue;
                    }
                    if(isset($postParams[$module['module']][$keyMod])) {
                        $singleModule[$keyMod] = $postParams[$module['module']][$keyMod] == 'on' ? 1 : $postParams[$module['module']][$keyMod];
                    } else {
                        $singleModule[$keyMod] = 0;
                    }
                }
                $modules2[] = $singleModule;
            }

            $accessMatrix = ['access'=>json_encode($modules2), 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s')];

            $updated = $this->rm->updateAccessMatrix($roleId, $accessMatrix);

            if($updated) {
                $this->session->set_flashdata('success', 'Permissions updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Permissions update failed');
            }

            redirect('roleListing/edit/'.$roleId);
        }
    }
}
