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

        if (empty($results)) {
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

/**
 * Summary: Returns a result based on a single ID
 * Notes: Returns the result identified by &#x60;resultId&#x60;.
 *
 * @SWG\Get(
 *     method      = "GET",
 *     path        = "/results/{resultId}",
 *     tags        = { "Results" },
 *     summary     = "Returns a result based on a single ID",
 *     description = "Returns the result identified by `resultId`.",
 *     operationId = "miw_get_results",
 *     parameters  = {
 *          { "$ref" = "#/parameters/resultId" }
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "Result",
 *          schema      = { "$ref": "#/definitions/Result" }
 *     ),
 *     @SWG\Response(
 *          response    = 404,
 *          description = "Result id. not found",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 */
$app->get(
    '/results/{id:[0-9]+}',
    function ($request, $response, $args) {
        $this->logger->info('GET \'/results/' . $args['id'] . '\'');
        $result = getEntityManager()
            ->getRepository('MiW16\Results\Entity\Result')
            ->findOneById($args['id']);

        if (empty($result)) {
            $newResponse = $response->withStatus(404);
            $datos = array(
                'code' => 404,
                'message' => 'Result not found'
            );
            return $this->renderer->render($newResponse, 'message.phtml', $datos);
        }

        return $response->withJson($result);
    }
)->setName('miw_get_results');

/**
 * Summary: Deletes a result
 * Notes: Deletes the result identified by &#x60;resultId&#x60;.
 *
 * @SWG\Delete(
 *     method      = "DELETE",
 *     path        = "/results/{resultId}",
 *     tags        = { "Results" },
 *     summary     = "Deletes a result",
 *     description = "Deletes the result identified by `resultId`.",
 *     operationId = "miw_delete_results",
 *     parameters={
 *          { "$ref" = "#/parameters/resultId" }
 *     },
 *     @SWG\Response(
 *          response    = 204,
 *          description = "Result deleted &lt;Response body is empty&gt;"
 *     ),
 *     @SWG\Response(
 *          response    = 404,
 *          description = "Result not found",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 */
$app->delete(
    '/results/{id:[0-9]+}',
    function ($request, $response, $args) {
        $this->logger->info('DELETE \'/results/' . $args['id'] . '\'');
        $em = getEntityManager();
        $result = $em
            ->getRepository('MiW16\Results\Entity\Result')
            ->findOneById($args['id']);
        if (empty($result)) {
            $newResponse = $response->withStatus(404);
            $datos = array(
                'code' => 404,
                'message' => 'Result not found'
            );
            return $this->renderer->render($newResponse, 'message.phtml', $datos);
        } else {
            $em->remove($result);
            $em->flush();
        }

        return $response->withStatus(204);
    }
)->setName('miw_delete_users');

/**
 * Summary: Provides the list of HTTP supported methods
 * Notes: Return a &#x60;Allow&#x60; header with a list of HTTP supported methods.
 *
 * @SWG\Options(
 *     method      = "OPTIONS",
 *     path        = "/results",
 *     tags        = { "Results" },
 *     summary     = "Provides the list of HTTP supported methods",
 *     description = "Return a `Allow` header with a list of HTTP supported methods.",
 *     operationId = "miw_options_results",
 *     @SWG\Response(
 *          response    = 200,
 *          description = "`Allow` header &lt;Response body is empty&gt;",
 *     )
 * )
 */
$app->options(
    '/results',
    function ($request, $response, $args) {
        $this->logger->info('OPTIONS \'/results\'');

        return $response
            ->withHeader(
                'Allow',
                'OPTIONS, GET, POST, PUT, DELETE'
            );
    }
)->setName('miw_options_results');

/**
 * Summary: Creates a new result
 * Notes: Creates a new result
 *
 * @SWG\Post(
 *     method      = "POST",
 *     path        = "/results",
 *     tags        = { "Results" },
 *     summary     = "Creates a new result",
 *     description = "Creates a new result",
 *     operationId = "miw_post_result",
 *     parameters  = {
 *          {
 *          "name":        "data",
 *          "in":          "body",
 *          "description": "`Result` properties to add to the system",
 *          "required":    true,
 *          "schema":      { "$ref": "#/definitions/ResultData" }
 *          }
 *     },
 *     @SWG\Response(
 *          response    = 201,
 *          description = "`Created` Result created",
 *          schema      = { "$ref": "#/definitions/Result" }
 *     ),
 *     @SWG\Response(
 *          response    = 422,
 *          description = "`Unprocessable entity` Result or time is left out",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 */
$app->post(
    '/results',
    function ($request, $response, $args) {
        $this->logger->info('POST \'/results\'');
        $em = getEntityManager();
        $data = json_decode($request->getBody(), true); // parse the JSON into an assoc. array
        if (empty($data['result']) || empty($data['time'])) {
          $newResponse = $response->withStatus(422);
          $data = array(
              'code' => 422,
              'message' => 'Result or time is left out'
          );
          return $this->renderer->render($newResponse, 'message.phtml', $data);
        }else{
          $user = $em->getRepository('MiW16\Results\Entity\User')
                     ->findOneById($data['user_id']);
          $time = new \DateTime($data['time']);
          $result = new Result($data['result'],
                               $user,
                               $time);
          $em->persist($result);
          $em->flush();
        }
        return $response->withJson($result, 201);
    }
)->setName('miw_post_results');

/**
 * Summary: Updates a result
 * Notes: Updates the result identified by &#x60;resultId&#x60;.
 *
 * @SWG\Put(
 *     method      = "PUT",
 *     path        = "/results/{resultId}",
 *     tags        = { "Results" },
 *     summary     = "Updates a result",
 *     description = "Updates the result identified by `resultId`.",
 *     operationId = "miw_put_results",
 *     parameters={
 *          { "$ref" = "#/parameters/resultId" },
 *          {
 *          "name":        "data",
 *          "in":          "body",
 *          "description": "`Result` data to update",
 *          "required":    true,
 *          "schema":      { "$ref": "#/definitions/ResultData" }
 *          }
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "`Ok` Result previously existed and is now updated",
 *          schema      = { "$ref": "#/definitions/Result" }
 *     ),
 *     @SWG\Response(
 *          response    = 404,
 *          description = "`Not Found` The result could not be found",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     ),
 *     @SWG\Response(
 *          response    = 422,
 *          description = "`Unprocessable entity` Result or time is left out",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 */
$app->put(
    '/results/{id:[0-9]+}',
    function ($request, $response, $args) {
        $this->logger->info('PUT \'/results\'');
        $em = getEntityManager();
        $data = json_decode($request->getBody(), true); // parse the JSON into an assoc. array
        $result = $em->getRepository('MiW16\Results\Entity\Result')->find($args['id']);
        if ($result) {
          if (empty($data['result']) || empty($data['time'])) {
            $newResponse = $response->withStatus(422);
            $datos = array(
                'code' => 422,
                'message' => 'Result or time is left out'
            );
            return $this->renderer->render($newResponse, 'message.phtml', $datos);
          }else{
            $user = $em->getRepository('MiW16\Results\Entity\User')
                       ->findOneById($data['user_id']);
            $time = new \DateTime($data['time']);
            $result->setResult($data['result']);
            $result->setUser($user);
            $result->setTime($time);
            $em->flush();
          }
        }else{
          $newResponse = $response->withStatus(404);
          $datos = array(
              'code' => 404,
              'message' => 'User not found'
          );
          return $this->renderer->render($newResponse, 'message.phtml', $datos);
        }
        return $response->withJson($result);
    }
)->setName('miw_post_users');
