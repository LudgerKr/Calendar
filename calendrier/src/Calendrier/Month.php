<?php
/**
 * Created by PhpStorm.
 * User: ludger
 * Calendrier: 27/10/18
 * Time: 10:52
 */
namespace Calendrier;
class Month
{

    public $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    private $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre',
        'Novembre', 'Décembre'];

    public $month;
    public $year;

    /**
     * @param int $month Le mois compris entre 1 et 12
     * @param int $year L'anéne
     * @throws \Exception
     */

    public function __construct(?int $month = null, ?int $year = null)
    {
        if ($month === null || $month < 1 || $month >= 13) {
            $month = intval(date('m'));
        }

        if ($year === null || $month < 1 || $month >= 13) {
            $year = intval(date('Y'));
        }
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Renvoie le premier jour du mois
     * @return \DateTime
     */

    public function getStartingDay(): \DateTime
    {
        return new \DateTime("{$this->year}-{$this->month}-01");
    }

    /**
     * Retourne le mois en lettre
     * @return string
     */
    public function toString(): string
    {
        return $this->months[$this->month - 1] . ' ' . $this->year;
    }

    /**
     * Renvoie le nombre de semaine dans le mois
     * @return int
     */

    public function getWeeks(): int
    {
        $start = $this->getStartingDay();
        $end = (clone $start)->modify('+1 month -1 day');
        $startWeek = intval($start->format('W'));
        $endWeek = intval($end->format('W'));
        if ($endWeek === 1) {
            $endWeek = intval((clone $end)->modify('- 7 days')->format('W')) +1;
        }

        $weeks = $endWeek - $startWeek +1;
        if($weeks < 0) {
            $weeks = intval($end->format('W'));
        }

        return $weeks;
    }

    /**
     * Le jour est-il dans le mois en cours ?
     * @param \DateTime $date
     * @return bool
     */

    public function withinMonth(\DateTime $date): bool
    {
        return $this->getStartingDay()->format('Y-m') === $date->format('Y-m');
    }

    /**
     * Renvoie le mois suivant
     * @return Month
     * @throws \Exception
     */

    public function nextMonth(): Month
    {
        $month = $this->month + 1;
        $year = $this->year;

        if ($month >= 13) {
            $month = 1;
            $year += 1;
        }

        return new Month($month, $year);
    }

    /**
     * Renvoie le mois précédent
     * @return Month
     * @throws \Exception
     */

    public function previousMonth(): Month
    {
        $month = $this->month - 1;
        $year = $this->year;
        if ($month < 1) {
            $month = 12;
            $year -= 1;
        }

        return new Month($month, $year);
    }

}