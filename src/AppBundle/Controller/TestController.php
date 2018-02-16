<?php
/**
 * Created by PhpStorm.
 * User: J14007_m
 * Date: 2017/11/07
 * Time: 9:56
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class TestController extends Controller
{

    /**
     * @Route("/test/{genusName}")
     **/

    public function showAction($genusName)
    {
        try {
            $sql = new \PDO ('mysql:dbname=symfony; host=127.0.0.1;port=3306; charset=utf8', 'root', '');
            echo '接続に成功しました。';
        } catch (PDOException $e) {
            echo "接続エラー:{$e->getMessage()}";
        }

        $stmt = $sql->query("select * from symfony.task");
        $count = 0;
        $array[] = "";
        foreach ($stmt as $row) {
            $array[$count] = 'Task: ' . $row['task'] . '   Time: ' . $row['datetime'];
            $count++;
        }

        $sql = null;
        return $this->render('test/test.html.twig', [
            'name' => $genusName,
            'arrays' => $array
        ]);
    }
}