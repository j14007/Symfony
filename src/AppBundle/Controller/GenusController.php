<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 *
 */
class GenusController extends Controller
{
    /**
     * @Route("/genus/")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function showAction(Request $request)
    {
        try {
            $sql = new \PDO ('mysql:dbname=symfony; host=127.0.0.1;port=3306; charset=utf8', 'root', '');
            echo '接続に成功しました。';
        } catch (PDOException $e) {
            echo "接続エラー:{$e->getMessage()}";
        }

        $task = new Task();

        $form = $this->createFormBuilder($task)
            //getとpostの違いで苦戦
            ->setMethod('GET')
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        $taskdata = $form->get('task')->getViewData();
        $duedate = $form->get('dueDate')->getViewData();
        $due = $duedate['year'].'-'.$duedate['month'].'-'.$duedate['day'];

        if ($form->isSubmitted() && $form->isValid()) {
            $insert = $sql->prepare("insert into symfony.task(task, datetime) VALUES (:task,:due)");
            $insert->bindParam(':task',$taskdata);
            $insert->bindValue(':due',$due);
            $insert->execute();
        }

        $stmt = $sql->query("select * from symfony.task");
        $count = 0;
        $array[] = "";
        foreach ($stmt as $row) {
            $array[$count] = 'Task: ' . $row['task'] . '   Time: ' . $row['datetime'];
            $count++;
        }

        $sql = null;

        return $this->render('genus/show.html.twig', [
            'arrays' => $array,
            'form' => $form->createView(),
            'task' => $taskdata,
            'duedate' => $due
        ]);
    }
}

?>
