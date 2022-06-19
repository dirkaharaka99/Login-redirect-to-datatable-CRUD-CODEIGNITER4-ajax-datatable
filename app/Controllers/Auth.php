<?php namespace App\Controllers;
 
use CodeIgniter\Controller;
use App\Models\UserModel;
 
class Auth extends BaseController
{
   public function index()
   {
	return view('login');
   }

   public function login()
   {
      $data = $this->request->getPost();
      $validate = $this->validation->run($data, 'login');
      $errors= $this->validation->getErrors();

      if ($errors){
         return $this->response->setJSON($errors)->setStatusCode(400);
      }
      else{
         $cekuser = $this->login->where('email', $data['email'])->first();
         if($cekuser){
               if($cekuser['password'] == $data['password']){
                  $session = [
                     'email' =>$cekuser['email'],
                  ];

                  session()->set($session);
                  return $this->response->setJSON(['messages'=> 'Login berhasil']);
         
               }else{
                  return $this->response->setJSON(['error' => 'Password anda salah'])->setStatusCode(404);
               }

         }else{
            return $this->response->setJSON(['error' => 'Email tidak tersedia'])->setStatusCode(404);
         }
      }
   }

   public function logout()
   {
      session()->destroy();
      return redirect()->to('./');
   }
	
} 