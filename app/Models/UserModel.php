<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $useTimestamps = true;
    protected $allowedFields = ['nama', 'email','password', 'gambar'];

    public function getRulesValidation($method = null)
    {
        if ($method == 'save') {
            $imgRules = 'uploaded[gambar]|max_size[gambar, 1024]|is_image[gambar]|ext_in[gambar,png,jpg,jpeg]';
            $emailRules = 'required|is_unique[user.email]';
            $passwordRules = 'required|is_unique[user.password]';
        } else {
            $imgRules = 'max_size[gambar, 1024]|is_image[gambar]|ext_in[gambar,png,jpg,jpeg]';
            $emailRules = 'required';
            $passwordRules = 'required';
        }

        $rulesValidation = [
            'nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ],
            'email' => [
                'rules' => $emailRules,
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'is_unique' => '{field} sudah digunakan.'
                ]
            ],
            'password' => [
                'rules' => $passwordRules,
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'is_unique' => '{field} sudah digunakan.'
                ]
            ],
            'gambar' => [
                'rules' => $imgRules,
                'errors' => [
                    'uploaded' => '{field} harus diisi.',
                    'max_size' => '{field} melebihi ukuran yang ditentukan',
                    'is_image' => 'format {field} tidak sesuai.',
                    'ext_in' => 'hanya format JPG, PNG yang diijinkan.'
                ]
            ]
        ];

        return $rulesValidation;
    }

    public function ajaxGetData($start, $length)
    {
        $result = $this->orderBy('nama', 'asc')
            ->findAll($start, $length);

        return $result;
    }

    public function ajaxGetDataSearch($search, $start, $length)
    {
        $result = $this->like('nama', $search)
            ->orLike('email', $search)
            ->findAll($start, $length);

        return $result;
    }

    public function ajaxGetTotal()
    {
        $result = $this->countAll();

        if (isset($result)) {
            return $result;
        }

        return 0;
    }

    public function ajaxGetTotalSearch($search)
    {
        $result = $this->like('nama', $search)
            ->orLike('email', $search)
            ->countAllResult();

        return $result;
    }
}