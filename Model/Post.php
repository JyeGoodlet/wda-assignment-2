<?php

/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/5/2015
 * Time: 8:24 PM
 */
class Post
{

    public $id;

    public $date;

    public $title;

    public $content;

    //hold the subcateogry details
    public $subcategory;

    //hold the user
    public $user;

    //this records the last time a comment when posted. The first value should be the timestamp when the thread was
    //created
    public $lastActivity;


}