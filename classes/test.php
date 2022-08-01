namespace Avado\MoodleAbstractionLibrary\Entities;

use Avado\MoodleAbstractionLibrary\Database\Builder;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class BaseModel extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string