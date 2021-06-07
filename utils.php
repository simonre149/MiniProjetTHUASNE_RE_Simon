<?php
    class Device
    {
        private $name;
        private $firmwareVersion;
        private $user;

        function __construct(string $name, string $firmwareVersion)
        {
            $this->name = $name;
            $this->firmwareVersion = $firmwareVersion;
        }

        function getName() { return $this->name; }
        function getFirmwareVersion() { return $this->firmwareVersion; }
        function getUser() { return $this->user; }
        
        function setUser(?User $user) { $this->user = $user; }

        function updateFirmware(string $newVersion, Cloud $cloud)
        {
            // on vérifie que la version du firmware est bien une version existante dans le cloud
            if (in_array($newVersion, $cloud->getAllFirmwareVersions()))
                $this->firmwareVersion = $newVersion;
            else
                showAlert("Firmware version " . $newVersion . " does not exist on this cloud. Do yourCloud.addFirmwareVersion(newVersion) before.");
        }
    }

    class User
    {
        private $name;
        private $userDevices = [];

        function __construct(string $name)
        {
            $this->name = $name;
        }

        function getName() { return $this->name; }
        function getUserDevices() {return $this->userDevices; }

        function addDeviceToUser(Device $device)
        {
            array_push($this->userDevices, $device);
        }

        function removeDeviceFromUser(Device $device)
        {
            unset($device, $this->userDevices);
        }
    }

    class Cloud
    {
        private $allDevices = [];
        private $allFirmwareVersions = [];

        function getAllDevices(){ return $this->allDevices; }
        function getAllFirmwareVersions(){ return $this->allFirmwareVersions; }

        function addFirmwareVersion(string $name)
        {
            array_push($this->allFirmwareVersions, $name);
        }

        function addDevice(Device $device)
        {
            // on vérifie que la device n'est pas déjà ajoutée
            if (!in_array($device, $this->allDevices))
                // si ce n'est pas le cas, on l'ajoute
                array_push($this->allDevices, $device);
                else
                    showAlert($device->getName() . " already added to cloud.");
        }

        function addRelation(Device $device, User $user)
        {
            // si le device ne possède pas d'utilisateur
            if ($device->getUser() == null)
            {
                // on peut le set
                $device->setUser($user);
                // et ajouter le device à la liste de devices de l'utilisateur
                $user->addDeviceToUser($device);
            }
            else
                showAlert('Device ' . $device->getName() . ' already has a user: ' . $device->getUser()->getName());
        }

        function removeRelation(Device $device, User $user)
        {
            // si le device a bel et bien un utilisateur défini et que le device apparaît dans la liste de devices de l'utilisateur
            if ($device->getUser() == $user && in_array($device, $user->getUserDevices()))
            {
                // on enlève l'utilisateur dans le device
                $device->setUser(null);
                // on enlève le device de la liste de devices de l'utilisateur
                $user->removeDeviceFromUser($device);
            }
            else
                showAlert('Cannot remove relation from device ' . $device->getName() . ' to ' . $user->getName());
        }
    }

    // petit utilitaire
    function showAlert(string $message)
    {
        echo("<script>alert('" . $message . "')</script>");
    }
?>