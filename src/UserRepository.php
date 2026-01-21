<?php
class UserRepository
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([]));
        }
    }

    private function readAll()
    {
        $json = file_get_contents($this->file);
        $data = json_decode($json, true);
        if (!is_array($data)) {
            $data = [];
        }
        return $data;
    }

    private function writeAll($data)
    {
        $fp = fopen($this->file, 'c+');
        if (!$fp) {
            throw new Exception('Unable to open data file');
        }
        flock($fp, LOCK_EX);
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    public function getAll()
    {
        return $this->readAll();
    }

    public function create($user)
    {
        $data = $this->readAll();
        $maxId = 0;
        foreach ($data as $u) {
            if (isset($u['id']) && is_numeric($u['id'])) {
                $maxId = max($maxId, (int)$u['id']);
            }
        }
        $user['id'] = $maxId + 1;
        $data[] = $user;
        $this->writeAll($data);
        return $user;
    }

    public function update($id, $new)
    {
        $data = $this->readAll();
        foreach ($data as &$u) {
            if ((string)$u['id'] === (string)$id) {
                $u['name'] = $new['name'];
                $u['email'] = $new['email'];
                $u['phone'] = $new['phone'];
                $this->writeAll($data);
                return $u;
            }
        }
        return null;
    }

    public function delete($id)
    {
        $data = $this->readAll();
        $found = false;
        $filtered = [];
        foreach ($data as $u) {
            if ((string)$u['id'] === (string)$id) {
                $found = true;
                continue;
            }
            $filtered[] = $u;
        }
        if ($found) {
            $this->writeAll($filtered);
        }
        return $found;
    }
}
