<?php

declare(strict_types=1);

namespace SaatchiArt\BentoBoxDDD\Ports\Http;

use Psr\Http\Message\RequestInterface;
use SaatchiArt\BentoBoxDDD\AppServices\UserFacade;

final class UserController
{
    /** @var UserFacade */
    private $userFacade;

    /**
     * @param UserFacade $userFacade
     */
    public function __construct(UserFacade $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    public function goOnVacation(RequestInterface $request)
    {
        $requestBody = \json_decode($request->getBody()->getContents(), true);
        $userId = $requestBody['user_id'];

        $this->userFacade->putUserOnVacation($userId);

        return \response()->json(['__metadata__' => 'User put on vacation'], 200);
    }

}
