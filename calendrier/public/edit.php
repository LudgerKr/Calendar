<?php

require '../src/bootstrap.php';
$pdo = get_pdo();
$events = new \Calendrier\Events($pdo);
$errors = [];
if (!isset($_GET['id'])) {
    e404();
}

try {
    $event = $events->find($_GET['id']);
} catch (\Exception $e) {
    e404();
}

$data = [
        'name' => $event->getName(),
    'date' => $event->getStart()->format('Y-m-d'),
    'start' => $event->getStart()->format('H:i'),
    'end' => $event->getEnd()->format('H:i'),
    'description' => $event->getDescription()
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $validator = new Calendrier\EventValidator();
    $errors = $validator->validates($data);
    if(empty($errors)) {
        $event->setName($data['name']);
        $event->setDescription($data['description']);
        $event->setStart($data['description']);
        $event->setEnd($data['description']);
        $event->setStart(DateTime::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['start'])
            ->format('Y-m-d H:i:s'));
        $event->setEnd(DateTime::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['end'])
            ->format('Y-m-d H:i:s'));
        $events->update($event);
        header('Location:  /Calendrier/public/');
        exit();

    }
}

render('header', ['title'=> $event->getName()]);
?>

<div class="container">

    <h1>Modifier : <small><?= ($event->getName()); ?></small></h1>

    <form action="" method="post" class="form">
        <?php render('calendrier/form', ['data' => $data, 'errors' => $errors]); ?>
        <div class="form-group">
            <button class="btn btn-primary">Modifier votre évènement</button>
        </div>
    </form>

</div>

<?php render('footer'); ?>
