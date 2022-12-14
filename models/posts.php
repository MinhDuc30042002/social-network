<?php

class Posts
{
    public $id;
    public $content;
    public $image;
    public $user_id;
    public $title;
    public $name;


    public function __construct($id, $content, $image, $user_id, $title, $name)
    {
        $this->id = $id;
        $this->content = $content;
        $this->image = $image;
        $this->user_id = $user_id;
        $this->title = $title;
        $this->name = $name;
    }

    public static function all()
    {
        $db = DB::getInstance();

        $query = 'SELECT u.id as id_user, name, p.id as id_posts, image, content, title FROM users as u JOIN posts as p ON u.id = p.user_id ORDER BY p.id DESC';
        $req = $db->query($query)->fetchAll();

        return $req;
    }

    public static function posts($id)
    {
        $db = DB::getInstance();

        $query = "SELECT p.* FROM posts as p INNER JOIN users as u ON p.user_id = u.id WHERE p.user_id = $id ORDER BY p.id DESC";
        $req = $db->query($query)->fetchAll();

        return $req;
    }

    public static function store($content, $title, $image, $user_id)
    {
        $query = "INSERT INTO posts (content, image, user_id, title) VALUES ('$content', '$image', $user_id, '$title')";
        $db = DB::getInstance();
        $req = $db->prepare($query)->execute();
        return $req;
    }

    public static function first($id)
    {
        $db = DB::getInstance();
        $query = "SELECT * FROM posts WHERE id = $id";
        $req = $db->prepare($query);
        $req->execute();

        $item = $req->fetch();
        return $item;
    }

    public static function update($title, $content, $id, $image)
    {
        $query = "UPDATE posts SET title = '$title', content = '$content', image = '$image' WHERE id = $id";
        $db = DB::getInstance();
        $req = $db->prepare($query)->execute();

        return $req;
    }

    public static function destroy($id)
    {
        $query = "DELETE FROM posts WHERE id = $id";
        $db = DB::getInstance();
        $req = $db->prepare($query)->execute();

        return $req;
    }

    public static function first_posts($id)
    {
        $posts = Posts::first($id);
        return $posts;
    }

    public static function all_comment_of_post($id)
    {
        $db = DB::getInstance();

        $query = "SELECT cmt.*, p.*, u.* FROM comments as cmt JOIN users as u ON cmt.user_id = u.id JOIN posts as p ON cmt.posts_id = p.id WHERE p.id = $id";
        $req = $db->prepare($query);
        $req->execute();

        $item = $req->fetchAll();
        return $item;
    }
    public static function all_comment($id)
    {
        $db = DB::getInstance();

        $query = "SELECT cmt.id as icmt, u.name, comment, cmt.created_at, posts_id ,user_id as uid, cmt.id_reply FROM comments as cmt 
        LEFT JOIN users as u ON cmt.user_id = u.id 
        WHERE posts_id = $id ORDER BY cmt.id DESC";
        $req = $db->prepare($query);
        $req->execute();

        $item = $req->fetchAll();
        return $item;
    }

    public static function pagination()
    {
        $number_of_result = 1;
        $rows = count(Posts::all());

        $page_result = ceil($rows / $number_of_result);
        return $page_result;
    }

    public static function limit_post($page)
    {
        $db = DB::getInstance();
        $number_of_result = 1;
        $result = ($page - 1) * $number_of_result;

        $query = "SELECT u.id as id_user, name, p.id as id_posts, image, content, title FROM users as u JOIN posts as p ON u.id = p.user_id ORDER BY p.id DESC LIMIT $result, $number_of_result";

        $req = $db->query($query)->fetchAll();
        return $req;
    }
}
