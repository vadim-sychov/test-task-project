<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\UserRegistrationMessage;;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use SocialTech\StorageInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/users", name="api_users_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/register", name="regitster", methods={"POST"})
     *
     * @param Request $request
     * @param StorageInterface $storage
     * @return JsonResponse
     */
    public function registerAction(Request $request, StorageInterface $storage, KernelInterface $kernel): JsonResponse
    {
        $rootPath = $kernel->getProjectDir();
        dump($storage->exists($rootPath . '/storage/users.json'));

//        if ($storage->exists($rootPath . '/storage/users.json')) {
//            $storage->append($rootPath . '/storage/users.json', json_encode(['nickname' => 'vadim', 'password' => '123']));
//        } else {
//            $storage->store($rootPath. '/storage/users.json', json_encode(['nickname' => 'vadim', 'password' => '123']));
//        }
//        file_put_contents('newfile.json', json_encode(['nickname' => 'vadim', 'password' => '123']));

//        dd('nice');
        //TODO попробовать заполнять UserRegistrationMessage через форму
        //TODO получать зависимость через интерфейс
        $userRegistrationMessage = new UserRegistrationMessage();
        $userRegistrationMessage->setFirstName($request->get('firstname'));
        $userRegistrationMessage->setLastName($request->get('lastname'));
        $userRegistrationMessage->setNickname($request->get('nickname'));
        $userRegistrationMessage->setPassport($request->get('password'));
        $userRegistrationMessage->setAge($request->request->getInt('age'));

        $this->dispatchMessage($userRegistrationMessage, [
                new AmqpStamp('user_create')
            ]);

        //TODO проверка на уникальность

        //TODO хешировать пароль после получения сообщения

        return new JsonResponse(['status' => 'success']);
    }
}