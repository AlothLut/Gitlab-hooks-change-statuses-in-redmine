<?php
namespace Controllers;

use Redmine\Client;
use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Webhook\InitialParams;
use Webhook\Decorators\MergeRequest;
use Webhook\Decorators\NewNote;


class ChangeRedmineStatus
{
    protected $logger;

    public static function init() {
        return new self();
    }

    public function __construct()
    {
        $this->setEnvironmentVar();
        $this->initLogger();

        try {
            $this->responseHandler();;
        } catch (Exception $e) {
            $this->logger->info('Response Handler error:', [$e]);
        }
    }

    /*
    * Loading environment variables.
    * You need to create a .env file in the root of the project.
    * Make sure it's in gitignore.
    * Content example:
    * REDMINE_API_KEY = "qwe12"
    */
    private function setEnvironmentVar()
    {
        $dotenv = Dotenv::createImmutable($_SERVER["DOCUMENT_ROOT"]);
        $dotenv->load();
    }

    private function initLogger()
    {
        $this->logger = new Logger('log');
        $this->logger->pushHandler(new StreamHandler($_SERVER["DOCUMENT_ROOT"].'/logs/set-redmine-status.log', Logger::DEBUG));
    }

    /*
    * Get merge request parameters from gitlab
    */
    private function responseHandler()
    {
        $jsonRequest = file_get_contents('php://input');
        $params = [
            "response" => $jsonRequest,
        ];

        $naiveInput = new InitialParams;
        $mr = new MergeRequest($naiveInput);
        $newNote = new NewNote($mr);
        $params = $newNote->formatParams($params);

        if ($params['id'] && $params['status']) {
            $redmine = new Client('http://redmine:3000/', $_ENV['REDMINE_API_KEY']);
            $errorSetStatus = $redmine->issue->setIssueStatus($params['id'], $params['status']);

            if ($errorSetStatus !== false) {
                $this->logger->info('errorSetStatus', [$errorSetStatus]);
            }

            if (!empty($params['url'])) {
                $errorAddNote = $redmine->issue->addNoteToIssue($params['id'], $params['url']);
                if ($errorAddNote !== false) {
                    $this->logger->info('errorAddNote', [$errorAddNote]);
                }
            }
        } else {
            $this->logger->info('Rdmaine status change failed, reponse params:', [$params]);
        }
    }
}
