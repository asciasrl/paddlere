<?php

namespace PaddlereBundle\Block;

use PaddlereBundle\Entity\FacilityManager;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;

class FacilitiesCreditBlockService extends AbstractBlockService
{

    /** @var  FacilityManager */
    protected $facilityManager;
    /**
     * @param string          $name
     * @param EngineInterface $templating
     */
    public function __construct($name = null, EngineInterface $templating = null, FacilityManager $facilityManager)
    {
        parent::__construct($name, $templating);
        $this->facilityManager = $facilityManager;
    }

        /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Facilties Credit';
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title' => 'Facilities Credit',
            'template' => 'PaddlereBundle:Block:facilities_credit_block.html.twig'
        ));
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();

        return $this->renderResponse($blockContext->getTemplate(), array(
            'facilities' => $this->facilityManager->findAllActiveWithCredit(),
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings
        ), $response);
    }

}