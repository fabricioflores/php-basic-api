<?php   // src/Entity/Result.php

namespace MiW16\Results\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Result
 *
 * @package MiW16\Results\Entity
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="results",
 *      indexes={
 *          @ORM\Index(name="FK_USER_ID_idx", columns={"user_id"})
 *      }
 *     )
 */
class Result implements \JsonSerializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="result", type="integer", nullable=false)
     */
    protected $result;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime", nullable=false)
     */
    protected $time;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    protected $user;

    /**
     * Result constructor.
     *
     * @param int       $result result
     * @param User      $user   user
     * @param \DateTime $time   time
     */
    public function __construct($result, $user, $time)
    {
        $this->id     = 0;
        $this->result = $result;
        $this->user   = $user;
        $this->time   = $time;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getResult(): int
    {
        return $this->result;
    }

    /**
     * @param int $result
     * @return Result
     */
    public function setResult($result): Result
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Result
     */
    public function setUser($user): Result
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     * @return Result
     */
    public function setTime(\DateTime $time): Result
    {
        $this->time = $time;
        return $this;
    }

    /**
     * Implements __toString()
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString(): string
    {
        return sprintf(
            '%3d - %3d - %30s - %s',
            $this->id,
            $this->result,
            $this->user,
            $this->time->format('Y-m-d H:i:s')
        );
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return array(
            'id'     => $this->id,
            'result' => $this->result,
            'user'   => $this->user,
            'time'   => $this->time
        );
    }
}

/**
 * @SWG\Definition(
 *     definition="Result",
 *     required = { "id", "result", "time" },
 *     @SWG\Property(
 *          property    = "id",
 *          description = "Result Id",
 *          type        = "integer",
 *          format      = "int32"
 *      ),
 *      @SWG\Property(
 *          property    = "result",
 *          description = "Result value",
 *          type        = "integer",
 *          format      = "int32"
 *      ),
 *      @SWG\Property(
 *          property    = "time",
 *          description = "Timestamp of creation",
 *          type        = "string",
 *          format      = "date"
 *      ),
 *      @SWG\Property(
 *          property    = "user_id",
 *          description = "User Id FK",
 *          type        = "integer",
 *          format      = "int32"
 *      ),
 *      example = {
 *          "id"       = 1508,
 *          "result"   = 10,
 *          "time"     = "2016-12-11 00:00:00",
 *          "user_id"  = 1
 *     }
 * )
 * @SWG\Parameter(
 *      name        = "resultId",
 *      in          = "path",
 *      description = "ID of result to fetch",
 *      required    = true,
 *      type        = "integer",
 *      format      = "int32"
 * )
 */

/**
 * @SWG\Definition(
 *      definition = "ResultData",
 *      @SWG\Property(
 *          property    = "result",
 *          description = "Result value",
 *          type        = "string"
 *      ),
 *      @SWG\Property(
 *          property    = "time",
 *          description = "Timestamp of creation",
 *          type        = "string"
 *      ),
 *      @SWG\Property(
 *          property    = "user_id",
 *          description = "user id",
 *          type        = "string"
 *      ),
 *      example = {
 *         "id" = 7,
 *         "result"= 0,
 *         "user"= {
 *            "id"= 5,
 *            "username"= "fabriciofloresgallardo",
 *            "email"= "fabricio@fabricio.com",
 *            "enabled"= false,
 *            "token"= "e11e396c511549a5e3c3d90d7d2131e00288c680"
 *        },
 *        "time"= {
 *           "date"= "2011-01-01 15:03:01.000000",
 *           "timezone_type"= 3,
 *           "timezone"= "Europe/Berlin"
 *        }
 *      }
 * )
 */

/**
 * Result array definition
 *
 * @SWG\Definition(
 *     definition = "ResultsArray",
 *      @SWG\Property(
 *          property    = "results",
 *          description = "Results array",
 *          type        = "array",
 *          items       = {
 *              "$ref": "#/definitions/Result"
 *          }
 *      )
 * )
 */
