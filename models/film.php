<?php

class Film extends Model {
    public function addFilm ($data) {
        if ( !isset($data['title']) || !isset($data['release_year']) || !isset($data['format_id']) || !isset($data['actor0'])) {
            return false;
        }

        $data_strip = array_map('strip_tags', $data);
        $userdata = array_map('htmlentities', $data_strip);

        $title = $userdata['title'];
        $release_year = $userdata['release_year'];
        $format_id = $userdata['format_id'];
        $actors = [];

        foreach ($userdata as  $key => $value) {
            if (stripos($key, 'actor') !== false) {
                $actors[] = $value;
            }
        }

       //Add new record
            $sql = "
            insert into film
              set title = :title,
                  release_year = :release_year,
                  format_id = :format_id
            ";

        $this->db->execute($sql, array(':title' => $title, ':release_year' => $release_year, ':format_id' => $format_id), true);

        $sql = "SET @film_id = LAST_INSERT_ID()";
        $this->db->execute($sql, null, true);

        foreach ($actors as $value) {
            $sql = "INSERT IGNORE INTO actor (name) VALUES (:name)";
            $this->db->execute($sql, array(':name' => $value), true);
            $sql = "SET @actor_id = LAST_INSERT_ID()";
            $this->db->execute($sql, null, true);
            $sql = "INSERT INTO film_actor (film_id,actor_id) VALUES(@film_id, @actor_id)";
            $this->db->execute($sql, null, true);
        }

        return true;

    }

    public function getListOrderedByTitle(array $data = null) {
        if (isset($data)) {
            $userdata = array_map('htmlentities', $data);
        }

        if (isset($userdata["title"])) {
            $title = $userdata["title"];

            $sql = "select fl.id, fl.title,fl.release_year, f.format from film fl
                    inner join format f on f.id=fl.format_id
                    where fl.title = :title order by fl.title";

            $result = $this->db->execute($sql, array(':title' => $title));
            return $result;
        } elseif (isset($userdata["name"])) {
            $name = $userdata["name"];

            $sql = "select fl.id, fl.title,fl.release_year, f.format from film fl
                    inner join format f on f.id=fl.format_id
                    inner join film_actor fa on fa.film_id=fl.id
                    inner join actor a on a.id=fa.actor_id
                    where a.name = :name order by fl.title";

            $result = $this->db->execute($sql, array(':name' => $name));
            return $result;
        }

        $sql = "select fl.id, fl.title,fl.release_year, f.format from film fl
                inner join format f on f.id=fl.format_id
                where 1 order by fl.title";

        $result = $this->db->execute($sql);
        return $result;

    }

    public function getFormats() {
        $sql = "select * from format
                    where 1 ";

        $result = $this->db->execute($sql);
        return $result;
    }

    public function getById($id) {
        $sql = "select fl.id, fl.title,fl.release_year, f.format from film fl
                    inner join format f on f.id=fl.format_id
                    where fl.id = :id ";

        $result = $this->db->execute($sql, array(':id' => $id));
        return $result;
    }

    public function getActors($id) {
        $sql = "select a.name from actor a
                inner join film_actor fa on fa.actor_id=a.id
                    where fa.film_id = :id ";

        $result = $this->db->execute($sql, array(':id' => $id));
        return $result;
    }

    public function delete ($id) {
        $sql = "delete from film where id = :id";
        $result = $this->db->execute($sql, array(':id' => $id), true);
        return $result;
    }

    public function import($uploadfile) {

        $formats = $this->getFormats();

        $lines = file($uploadfile, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
        $count = count($lines);

        for($i = 0; $i < $count; $i +=4) {
            $output = array_slice($lines,$i,4);

            $release_year_arr = explode(": ", $output[1]);
            $format_arr = explode(": ", $output[2]);
            $actors_arr = explode(": ", $output[3]);

            $actors = explode(", ", $actors_arr[1]);

            $title = substr($output[0], stripos($output[0], ':') + 2); $release_year = $release_year_arr[1]; $format = $format_arr[1];


            foreach ($formats as $value) {
                if ($format == $value['format']) {
                    $format_id = $value['id'];
                }
            }

            $sql = "
            insert into film
              set title = :title,
                  release_year = :release_year,
                  format_id = :format_id
            ";

            $this->db->execute($sql, array(':title' => $title, ':release_year' => $release_year, ':format_id' => $format_id), true);

            $sql = "SET @film_id = LAST_INSERT_ID()";
            $this->db->execute($sql, null, true);

            foreach ($actors as $value) {
                $sql = "INSERT IGNORE INTO actor (name) VALUES (:name)";
                $this->db->execute($sql, array(':name' => $value), true);
                $sql = "SET @actor_id = LAST_INSERT_ID()";
                $this->db->execute($sql, null, true);
                $sql = "INSERT INTO film_actor (film_id,actor_id) VALUES(@film_id, @actor_id)";
                $this->db->execute($sql, null, true);
            }

        }

        return true;
    }

}