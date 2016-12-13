<?php // apiResultsDoctrine - src/routes_results.php

use Swagger\Annotations as SWG;

use MiW16\Results\Entity\Result;
use MiW16\Results\Entity\User;

/**
 * Summary: Returns all results
 * Notes: Returns all results from the system that the result has access to.
 *
 * @SWG\Get(
 *     method      = "GET",
 *     path        = "/results",
 *     tags        = { "Results" },
 *     summary     = "Returns all results",
 *     description = "Returns all results from the system that the result has access to.",
 *     operationId = "miw_cget_results",
 *     @SWG\Response(
 *          response    = 200,
 *          description = "Result array response",
 *          schema      = { "$ref": "#/definitions/ResultsArray" }
 *     ),
 *     @SWG\Response(
 *          response    = 404,
 *          description = "Result object not found",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 * @var \Slim\App $app
 */
$app->get(
    '/results',
    function ($request, $response, $args) {
        $this->logger->info('GET \'/results\'');
        $results = getEntityManager()
            ->getRepository('MiW16\Results\Entity\Result')
            ->findAll();

        if (empty($results)) { // 404 - result object not found
            $newResponse = $response->withStatus(404);
            $datos = array(
                'code' => 404,
                'message' => 'result object not found'
            );
            return $this->renderer->render($newResponse, 'message.phtml', $datos);
        }

        return $response->withJson(array('results' => $results));
    }
)->setName('miw_cget_results');
