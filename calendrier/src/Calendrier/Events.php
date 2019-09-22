<?php
/**
 * Created by PhpStorm.
 * User: ludger
 * Date: 27/10/18
 * Time: 14:20
 */

namespace Calendrier;


class Events

{

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupères les évenemtns commençant entre 2 dates
     * @param \DateTime $start
     * @param \DateTime $end
     * @return array
     */
    public function getEventsBetween(\DateTime $start, \DateTime $end): array
    {
        $sql = "SELECT * FROM events WHERE start BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND
        '{$end->format('Y-m-d 23:59:59')}'";
        $statement = $this->pdo->query($sql);
        $results = $statement->fetchAll();
        return $results;
    }

    /**
     * Récupères les évenemtns commençant entre 2 dates indexé par jours
     * @param \DateTime $start
     * @param \DateTime $end
     * @return array
     */

    public function getEventsBetweenByDay (\DateTime $start, \DateTime $end): array
    {
        $events = $this->getEventsBetween($start, $end);
        $days = [];
        foreach($events as $event) {
            $date = explode(' ', $event['start']) [0];
            if (!isset($days[$date])) {
                $days[$date] = [$event];
            } else {
                $days[$date][] = $event;
            }
        }

        return $days;
    }

    /**
     * Récupere un événement
     * @param int $id
     * @return Event
     * @throws \Exception
     */

    public function find(int $id) : Event
    {
        require 'Event.php';
        $statement = $this->pdo->query("SELECT * FROM events WHERE id = $id LIMIT 1");
        $statement->setFetchMode(\PDO::FETCH_CLASS, Event::class);
        $result = $statement->fetch();
        if ($result === false) {
            throw  new \Exception('Aucun résultat n\'a été trouvé');
        }

        return $result;
    }

    /**
     * Crée un évènement dans la base de données
     * @param Event $event
     * @return bool
     */

    public function create (Event $event): bool
    {
        $statement = $this->pdo->prepare('INSERT INTO events (name, description, start, end) VALUES (?,?,?,?)');
        return $statement->execute([
            $event->getName(),
            $event->getDescription(),
            $event->getStart()->format('Y-m-d H:i:s'),
            $event->getEnd()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Met à jour l'évènement dans la base de données
     * @param Event $event
     * @return bool
     */

    public function update (Event $event): bool
    {
        $statement = $this->pdo->prepare('UPDATE events SET name = ?, description = ?, start = ?, 
end = ? WHERE id = ?');
        return $statement->execute([
            $event->getName(),
            $event->getDescription(),
            $event->getStart()->format('Y-m-d H:i:s'),
            $event->getEnd()->format('Y-m-d H:i:s'),
            $event->getId()
        ]);
    }

}