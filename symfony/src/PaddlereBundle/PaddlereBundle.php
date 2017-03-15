<?php

namespace PaddlereBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PaddlereBundle extends Bundle
{
    const EVENT_TYPE_CHOICES = array('Libero' => 0, 'Affitto' => 1, 'Lezione' => 2, 'Promo' => 3);
}
