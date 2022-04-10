<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\EmployeeModel;

class EmployeeController extends ResourceController
{
   //post
   public function createEmployee()
   {
     //formdatavalues
     //validation
     //return validation
     //instanceof model
    //save inside database table
    $rules = [
        'name' => "required",
        'email' => "required|valid_email|is_unique[employees.email]",
        ];
        if(!$this->validate($rules)){
        //error
        $response = [
          'status' => 500,
          'message' => $this->validator->getErrors(),
          'error' => true,  
          'data'=> []
        ];
        } else {
        //no error
         $file = $this->request->getFile('profile_image');
         if(!empty($file)){
            $image_name = $file->getName();
         $temp = explode(".", $image_name);
         $newImageName = round(microtime(true)) . '.' . end($temp);
         if($file->move("images", $newImageName)){
         //iamge has been uploaded
         $emp_obj = new EmployeeModel();
         $data = [
            'name' => $this->request->getVar('name'),
            'email' => $this->request->getVar('email'),
            'profile_image' => './images'.$newImageName
         ];
         if($emp_obj->insert($data)){
             //success - data has been saved
             $response = [
                    'status' => 200,
                    'message' => 'Employee has been created successfully',
                    'error' => false,
                    'data' => []
             ];
         } else {
            //failed to upload data
            $response = [
                    'status' => 500,
                    'message' => 'Failed to create employee',
                    'error' => true,
                    'data' => []
            ];
         }
         } else {
             //failed to upload image
             $response = [
                    'status' => 500,
                    'message' => 'Failed to upload image',
                    'error' => true,
                    'data' => []
             ];
         }
         } else {
            $emp_obj = new EmployeeModel();
            $data = [
               'name' => $this->request->getVar('name'),
               'email' => $this->request->getVar('email'),
            //    'profile_image' => './images'.$newImageName
            ];
            if($emp_obj->insert($data)){
                //success - data has been saved
                $response = [
                       'status' => 200,
                       'message' => 'Employee has been created successfully',
                       'error' => false,
                       'data' => []
                ];
            } else {
               //failed to upload data
               $response = [
                       'status' => 500,
                       'message' => 'Failed to create employee',
                       'error' => true,
                       'data' => []
               ];
            }
         }
        
        }
        return $this->respondCreated($response);         
   }

   //get
   public function listemployees()
   {
     $emp_obj = new EmployeeModel();
     $response = [
        'status' => 200,
        'message' => 'Employee list',
        'error' => false,
        'data' => $emp_obj->findAll()
    ]; 
    return $this->respondCreated($response);
   }
   //get
   public function getEmployee($emp_id)
   {
       $emp_obj = new EmployeeModel();
       $emp_data = $emp_obj->find($emp_id);
       if(!empty($emp_data)){
           $response = [
               'status' => 200,
               'message' => 'Employee data',
                'error' => false,
                'data' => $emp_data
                ];
            } else {
                $response = [
                    'status' => 500,
                    'message' => 'Employee not found',
                    'error' => true,
                    'data' => []
                ];
            }
            return $this->respondCreated($response);
   }
   //put
   public function updateEmployee($emp_id){
      //validation
      //emp exists
      //update employee 
      //404
      $rules = [
        'name' => "required",
        'email' => "required|valid_email",
      ];
      if(!$this->validate($rules)){
       //validation errors
         $response = [
            'status' => 500,
            'message' => $this->validator->getErrors(),
            'error' => true,
            'data' => []
            ];
        
    } else {
        //we have no error
        $emp_obj = new EmployeeModel();
        $emp_data = $emp_obj->find($emp_id);
        if(!empty($emp_data)){
            $file = $this->request->getFile('profile_image');
            if(!empty($file)){
                $image_name = $file->getName();
                $temp = explode(".", $image_name);
                $newImageName = round(microtime(true)) . '.' . end($temp);
                if($file->move("images", $newImageName)){
                 //file has been uploaded 
                    $updated_data = [
                        'name' => $this->request->getVar('name'),
                        'email' => $this->request->getVar('email'),
                        'profile_image' => './images'.$newImageName
                    ];
                    //update data
                    $emp_obj -> update($emp_id, $updated_data);
                    $response = [
                        'status' => 200,
                        'message' => 'Employee has been updated successfully',
                        'error' => false,
                        'data' => []
                    ];
                } else {
                    //failed to upload image
                    $response = [
                        'status' => 500,
                        'message' => 'Failed to upload image',
                        'error' => true,
                        'data' => []
                    ];
                }
            } else {
                //there is no file uploaded
                $updated_data = [
                    'name' => $this->request->getVar('name'),
                    'email' => $this->request->getVar('email'),
                    // 'profile_image' => './images'.$newImageName 
                ];
                //update data
                $emp_obj -> update($emp_id, $updated_data);
                $response = [
                    'status' => 200,
                    'message' => 'Employee has been updated successfully',
                    'error' => false,
                    'data' => []
                ];

            }

        } else {
            $response = [
                'status' => 404,
                'message' => 'Employee not found',
                'error' => true,
                'data' => []
            ];
        }
      }
    
    
    
    
    return $this->respondCreated($response);
}

   //delete
   public function deleteEmployee($emp_id){
      $emp_obj = new EmployeeModel();
      $emp_data = $emp_obj->find($emp_id); //check if employee exists
      if(!empty($emp_data)){
          $emp_obj->delete($emp_id);
            $response = [
                'status' => 200,    
                'message' => 'Employee has been deleted successfully',
                'error' => false,
                'data' => []
            ];
   } else {
         $response = [
                'status' => 404,
                'message' => 'Employee not found',
                'error' => true,
                'data' => []
         ];
   }
   return $this->respondCreated($response);
}
}