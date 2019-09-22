<?php

require '../src/bootstrap.php';
render('header', ['title' => 'Ajouter un événément']);
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $errors = [];
    $validator = new Calendrier\EventValidator();
    $errors = $validator->validates($_POST);
    if(empty($errors)) {
        $event = new \Calendrier\Event();
        $event->setName($data['name']);
        $event->setDescription($data['description']);
        $event->setStart($data['description']);
        $event->setEnd($data['description']);
        $event->setStart(DateTime::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['start'])
            ->format('Y-m-d H:i:s'));
        $event->setEnd(DateTime::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['end'])
            ->format('Y-m-d H:i:s'));
        $events = new \Calendrier\Events(get_pdo());
        $events->create($event);
        header('Location: /Calendrier/public/?succes=1');
        exit();

    }
}
?>
    <div class="container">
        <?php if(!empty($errors)): dd($errors) ?>
            <div class="alert alert-danger">
                Merci de corriger vos erreurs ^_^
            </div>
        <?php endif; ?>


    <h1>Ajouter un évènement</h1>
    <form action="" method="post" class="form">
        <?php render('calendrier/form', ['data' => $data, 'errors' => $errors]); ?>
        <div class="form-group">
            <button class="btn btn-primary">Ajouter votre évènement</button>
        </div>
    </form>
</div>
<?php render('footer'); ?>
