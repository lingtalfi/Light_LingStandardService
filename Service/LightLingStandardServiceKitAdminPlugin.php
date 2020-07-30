<?php


namespace Ling\Light_LingStandardService\Service;


use Ling\Light\ServiceContainer\LightServiceContainerInterface;
use Ling\Light_LingStandardService\Exception\LightLingStandardServiceException;
use Ling\Light_PluginInstaller\PluginInstaller\PluginInstallerInterface;
use Ling\Light_UserDatabase\Service\LightUserDatabaseService;
use Ling\SimplePdoWrapper\Util\Where;
use Ling\UniverseTools\PlanetTool;

/**
 * The LightLingStandardServiceKitAdminPlugin class.
 */
abstract class LightLingStandardServiceKitAdminPlugin implements PluginInstallerInterface
{

    /**
     * This property holds the container for this instance.
     * @var LightServiceContainerInterface
     */
    protected $container;

    /**
     * This property holds the options for this instance.
     *
     * Available options are:
     *
     *
     * @var array
     */
    protected $options;


    /**
     * The concrete class name.
     * This is only available after a call to the prepareTheNames method.
     * @var string
     */
    private $_className;


    /**
     * The exception class name.
     * This is only available after a call to the prepareTheNames method.
     * @var string
     */
    private $_exceptionClassName;


    /**
     * This property holds the _basePluginName for this instance.
     * @var string
     */
    private $_basePluginName;


    /**
     * Builds the LightLingStandardService01 instance.
     */
    public function __construct()
    {
        $this->options = [];
        $this->container = null;
    }


    /**
     * Sets the container.
     *
     * @param LightServiceContainerInterface $container
     */
    public function setContainer(LightServiceContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * Sets the options.
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    /**
     * @implementation
     */
    public function install()
    {
        if (true === $this->container->has("user_database")) {

            $this->prepareTheNames();
            /**
             * @var $userDb LightUserDatabaseService
             */
            $userDb = $this->container->get('user_database');
            $basePluginName = $this->_basePluginName;


            $permGroupApi = $userDb->getFactory()->getPermissionGroupApi();
            $permApi = $userDb->getFactory()->getPermissionApi();
            $groupAdminId = $permGroupApi->getPermissionGroupIdByName("Light_Kit_Admin.admin", null, true);
            $groupUserId = $permGroupApi->getPermissionGroupIdByName("Light_Kit_Admin.user", null, true);
            $adminId = $permApi->getPermissionIdByName("$basePluginName.admin", null, true);
            $userId = $permApi->getPermissionIdByName("$basePluginName.user", null, true);


            $userDb->getFactory()->getPermissionGroupHasPermissionApi()->insertPermissionGroupHasPermissions([
                [
                    'permission_group_id' => $groupAdminId,
                    'permission_id' => $adminId,
                ],
                [
                    'permission_group_id' => $groupUserId,
                    'permission_id' => $userId,
                ],
            ]);
        }
    }

    /**
     * @implementation
     */
    public function isInstalled(): bool
    {
        if (true === $this->container->has("user_database")) {
            $this->prepareTheNames();


            /**
             * @var $userDb LightUserDatabaseService
             */
            $userDb = $this->container->get('user_database');
            $permGroupApi = $userDb->getFactory()->getPermissionGroupApi();
            $basePluginName = $this->_basePluginName;
            $permApi = $userDb->getFactory()->getPermissionApi();
            $groupAdminId = $permGroupApi->getPermissionGroupIdByName("Light_Kit_Admin.admin", null, true);
            $adminId = $permApi->getPermissionIdByName("$basePluginName.admin", null, true);
            $res = $userDb->getFactory()->getPermissionGroupHasPermissionApi()->getPermissionGroupHasPermission(Where::inst()
                ->key("permission_group_id")->equals($groupAdminId)
                ->and()->key("permission_id")->equals($adminId)
            );
            if (false !== $res) {
                return true;
            }
        }
        return false;
    }

    /**
     * @implementation
     */
    public function uninstall()
    {
        if (true === $this->container->has("user_database")) {

            $this->prepareTheNames();
            /**
             * @var $userDb LightUserDatabaseService
             */
            $userDb = $this->container->get('user_database');
            $basePluginName = $this->_basePluginName;


            $permGroupApi = $userDb->getFactory()->getPermissionGroupApi();
            $permApi = $userDb->getFactory()->getPermissionApi();
            $groupAdminId = $permGroupApi->getPermissionGroupIdByName("Light_Kit_Admin.admin", null, true);
            $groupUserId = $permGroupApi->getPermissionGroupIdByName("Light_Kit_Admin.user", null, true);
            $adminId = $permApi->getPermissionIdByName("$basePluginName.admin", null, true);
            $userId = $permApi->getPermissionIdByName("$basePluginName.user", null, true);


            $userDb->getFactory()->getPermissionGroupHasPermissionApi()->deletePermissionGroupHasPermissionByPermissionGroupIdAndPermissionId($groupAdminId, $adminId);
            $userDb->getFactory()->getPermissionGroupHasPermissionApi()->deletePermissionGroupHasPermissionByPermissionGroupIdAndPermissionId($groupUserId, $userId);
        }
    }

    /**
     * @implementation
     */
    public function getDependencies(): array
    {
        return [];
    }



    //--------------------------------------------
    //
    //--------------------------------------------
    /**
     * Throws an exception.
     *
     * @param string $msg
     */
    protected function error(string $msg)
    {
        $this->prepareTheNames();
        throw new $this->_exceptionClassName($msg);
    }



    //--------------------------------------------
    //
    //--------------------------------------------
    /**
     * prepareTheNames names used by this class.
     */
    private function prepareTheNames()
    {
        if (null === $this->_className) {
            $className = get_class($this);
            $this->_className = $className;
            $p = explode('\\', $className);
            if (
                count($p) > 3 &&
                'Light' === $p[0] &&
                'Kit' === $p[1] &&
                'Admin' === $p[2]
            ) {


                $galaxy = array_shift($p);
                $planet = array_shift($p);
                $tightPlanetName = PlanetTool::getTightPlanetName($planet);
                $this->_exceptionClassName = implode('\\', [
                    $galaxy,
                    $planet,
                    'Exception',
                    $tightPlanetName . "Exception",
                ]);
                $this->_basePluginName = 'Light_' . substr($planet, 16);

            } else {
                throw new LightLingStandardServiceException("The class that extends LightLingStandardServiceKitAdminPlugin must follow the \"Ling Standard Service Kit Admin Plugin\" naming convention, see more details here: https://github.com/lingtalfi/Light_LingStandardService/blob/master/doc/pages/conception-notes.md#ling-standard-service-kit-admin-plugin");
            }
        }
    }

}