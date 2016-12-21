<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="paddlere__borghesiana_log")
 */
class BorghesianaLog
{

    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $dataora;

    /**
     * @var
     * @ORM\Column(type="string", length=32)
     *
     */
    private $evento;

    /**
     * @var
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $campo;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $inizio;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fine;

    /**
     * @var
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $durata;

    //  bin/console doctrine:generate:entities AppBundle/Entity/BorghesianaLog


    /**
     * Get id
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dataora
     *
     * @param \DateTime $dataora
     *
     * @return BorghesianaLog
     */
    public function setDataora($dataora)
    {
        $this->dataora = $dataora;

        return $this;
    }

    /**
     * Get dataora
     *
     * @return \DateTime
     */
    public function getDataora()
    {
        return $this->dataora;
    }

    /**
     * Set evento
     *
     * @param string $evento
     *
     * @return BorghesianaLog
     */
    public function setEvento($evento)
    {
        $this->evento = $evento;

        return $this;
    }

    /**
     * Get evento
     *
     * @return string
     */
    public function getEvento()
    {
        return $this->evento;
    }

    /**
     * Set campo
     *
     * @param string $campo
     *
     * @return BorghesianaLog
     */
    public function setCampo($campo)
    {
        $this->campo = $campo;

        return $this;
    }

    /**
     * Get campo
     *
     * @return string
     */
    public function getCampo()
    {
        return $this->campo;
    }

    /**
     * Set inizio
     *
     * @param \DateTime $inizio
     *
     * @return BorghesianaLog
     */
    public function setInizio($inizio)
    {
        $this->inizio = $inizio;

        return $this;
    }

    /**
     * Get inizio
     *
     * @return \DateTime
     */
    public function getInizio()
    {
        return $this->inizio;
    }

    /**
     * Set fine
     *
     * @param \DateTime $fine
     *
     * @return BorghesianaLog
     */
    public function setFine($fine)
    {
        $this->fine = $fine;

        return $this;
    }

    /**
     * Get fine
     *
     * @return \DateTime
     */
    public function getFine()
    {
        return $this->fine;
    }

    /**
     * Set durata
     *
     * @param integer $durata
     *
     * @return BorghesianaLog
     */
    public function setDurata($durata)
    {
        $this->durata = $durata;

        return $this;
    }

    /**
     * Get durata
     *
     * @return integer
     */
    public function getDurata()
    {
        return $this->durata;
    }
}
