<?php
class SettingsModel extends Model
{
    public function getCountryList()
    {
        return $this->select('countries', null, '*', 0);
    }

    public function setSettings($id, $data)
    {
        return $this->update('users', $data, "`id` = '$id' LIMIT 1");
    }

    public function getEmail()
    {
        $id = Request::getParam('user')->id;

        return $this->select('users', 'id = '.$id, 'email');
    }
}

/* End of file */