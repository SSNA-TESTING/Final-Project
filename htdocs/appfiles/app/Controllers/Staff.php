<?php
namespace App\Controllers;

use App\Models\UserModel;

class Staff extends BaseController
{
    private function rules(?int $id): array
    {
        return [
            'username'  => 'required|alpha_numeric|min_length[3]|max_length[50]|is_unique[users.username' . ($id ? ",id,$id" : '') . ']',
            'full_name' => 'required|max_length[100]',
            'password'  => ($id ? 'permit_empty' : 'required') . '|min_length[6]|max_length[72]',
            'avatar'    => 'permit_empty|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png,image/webp]|max_size[avatar,2048]',
        ];
    }

    public function index()
    {
        $staff = (new UserModel())
            ->select('users.*, (SELECT COUNT(*) FROM sales WHERE sales.sold_by = users.id) AS sales_count', false)
            ->orderBy('users.id', 'DESC')
            ->findAll();
        return view('staff/index', ['staff' => $staff]);
    }

    public function create() { return view('staff/form', ['member' => null]); }

    public function store()
    {
        if (! $this->validate($this->rules(null))) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        (new UserModel())->insert($this->data(null));
        return redirect()->to('/staff')->with('success', 'Staff member added to the holiday team!');
    }

    public function edit(int $id)
    {
        $u = (new UserModel())->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('staff/form', ['member' => $u]);
    }

    public function update(int $id)
    {
        $m = new UserModel();
        $u = $m->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if (! $this->validate($this->rules($id))) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        $m->update($id, $this->data($u));
        if ($id === (int) session()->get('user_id')) {
            $fresh = $m->find($id);
            session()->set(['user_name' => $fresh['full_name'], 'user_avatar' => $fresh['avatar']]);
        }
        return redirect()->to('/staff')->with('success', 'Staff member updated successfully!');
    }

    public function delete(int $id)
    {
        if ($id === (int) session()->get('user_id')) {
            return redirect()->to('/staff')->with('error', 'You cannot remove the account you are signed in with.');
        }
        if (db_connect()->table('sales')->where('sold_by', $id)->countAllResults() > 0) {
            return redirect()->to('/staff')->with('error', 'This staff member has recorded holiday sales and cannot be removed.');
        }
        $m = new UserModel();
        $u = $m->find($id);
        $m->delete($id);
        if ($u && $u['avatar']) @unlink(FCPATH . 'uploads/avatars/' . $u['avatar']);
        return redirect()->to('/staff')->with('success', 'Staff member removed.');
    }

    private function data(?array $old): array
    {
        $d = [
            'username'  => trim((string) $this->request->getPost('username')),
            'full_name' => trim((string) $this->request->getPost('full_name')),
        ];
        if ($pw = (string) $this->request->getPost('password')) $d['password'] = password_hash($pw, PASSWORD_DEFAULT);
        if ($img = save_upload($this->request->getFile('avatar'), 'avatars')) {
            $d['avatar'] = $img;
            if ($old && $old['avatar']) @unlink(FCPATH . 'uploads/avatars/' . $old['avatar']);
        }
        return $d;
    }
}
