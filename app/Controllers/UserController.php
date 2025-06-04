<?php 
namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    public function profile()
    {
        // Ensure user is logged in
        if (!session()->has('user_id')) {
            return $this->response->setJSON([
                'error' => 'Not authenticated'
            ])->setStatusCode(401);
        }
        
        $userId = session()->get('user_id');
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        
        if (!$user) {
            return $this->response->setJSON([
                'error' => 'User not found'
            ])->setStatusCode(404);
        }
        
        // Return user data without sensitive information
        return $this->response->setJSON([
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'created_at' => $user['created_at'],
            'last_login' => $user['last_login'] ?? null
        ]);
    }

    public function updateProfile()
    {
        // Ensure user is logged in
        if (!session()->has('user_id')) {
            return $this->response->setJSON([
                'error' => 'Not authenticated'
            ])->setStatusCode(401);
        }
        
        $userId = session()->get('user_id');
        $userModel = new UserModel();
        
        $data = $this->request->getJSON(true);
        
        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required|min_length[3]|max_length[30]',
            'email' => 'required|valid_email'
        ]);
        
        if (!$validation->run($data)) {
            return $this->response->setJSON([
                'errors' => $validation->getErrors()
            ])->setStatusCode(400);
        }
        
        // Update user
        if ($userModel->update($userId, $data)) {
            // Update session username if changed
            if (isset($data['username'])) {
                session()->set('username', $data['username']);
            }
            
            return $this->response->setJSON([
                'message' => 'Profile updated successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'error' => 'Failed to update profile'
        ])->setStatusCode(500);
    }
}