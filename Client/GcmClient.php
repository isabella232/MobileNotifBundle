<?php

/*
 * This file is part of the MobileNotifBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\MobileNotifBundle\Client;

use LinkValue\MobileNotif\Client\GcmClient as BaseGcmClient;
use LinkValue\MobileNotif\Model\Message;
use LinkValue\MobileNotifBundle\Profiler\ClientProfilableTrait;
use LinkValue\MobileNotifBundle\Profiler\NullClientProfiler;

/**
 * Google Cloud Messaging implementation.
 *
 * @package MobileNotifBundle
 * @author  Jamal Youssefi <jamal.youssefi@gmail.com>
 * @author  Valentin Coulon <valentin.c0610@gmail.com>
 */
class GcmClient extends BaseGcmClient
{
    use ClientProfilableTrait;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->clientProfiler = new NullClientProfiler();
    }

    /**
     * Push a notification to a mobile client.
     *
     * @param Message $message
     *
     * @throws \Exception if an Exception is thrown while pushing $message.
     */
    public function push(Message $message)
    {
        $profilingEvent = $this->clientProfiler->startProfiling(sprintf('GcmClient::push(%s)', $message->getPayloadAsJson()));

        try {
            parent::push($message);

            $this->clientProfiler->stopProfiling($profilingEvent, array(
                'error' => false,
                'error_message' => null,
            ));

        } catch (\Exception $e) {
            $this->clientProfiler->stopProfiling($profilingEvent, array(
                'error' => true,
                'error_message' => $e->getMessage(),
            ));

            throw $e;
        }
    }
}
