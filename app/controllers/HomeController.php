<?php

namespace App\controllers;

use Twig\Environment;
use Nette\Database\Connection;

class HomeController
{
  private $database;
  private $template;

  public function __construct(Connection $database, Environment $template)
  { 
    $this->database = $database;
    $this->template = $template;
  }

  public function index()
  {
    echo $this->template->render('index.twig');
  }
}
