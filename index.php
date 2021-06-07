<?php
    include 'utils.php';

    $cloud = new Cloud();
    // on indique à notre cloud les versions de firmware acceptées, on ajoute les plus récentes à la fin de la liste
    $cloud->addFirmwareVersion("FW-01");
    $cloud->addFirmwareVersion("FW-02");
    // on instancie les objets qui vont être nécessaires
    $SLB_01 = new Device("SLB-01", "FW-01");
    $SLB_02 = new Device("SLB-02", "FW-01");
    $SLB_03 = new Device("SLB-03", "FW-01");
    $User_01 = new User("User-01");
    $User_02 = new User("User-02");
    $User_03 = new User("User-03");

    // 01. Déclarer device avec Firmware : {SLB-01, FW-01}
    $cloud->addDevice($SLB_01);
    #$cloud->addDevice($SLB_01); //KO
    $cloud->addDevice($SLB_02);
    $cloud->addDevice($SLB_03);

    // 02. Associer device avec Patient : {SLB-01, User-01}
    $cloud->addRelation($SLB_01, $User_01);
    #$cloud->addRelation($SLB_01, $User_02); //KO
    $cloud->addRelation($SLB_02, $User_02);

    // 03. Dissocier device pour Patient : {User-01, SLB-01}
    #$cloud->removeRelation($SLB_03, $User_03); //KO
    $cloud->removeRelation($SLB_01, $User_01);

    // 04. Mettre à jour firmware : {SLB1-01, FW-02}, {SLB1-02, FW-02}  
    $SLB_01->updateFirmware("FW-02", $cloud);
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CloudManager</title>
    <meta name="description" content="CloudManager">
    <meta name="author" content="Simon RÉ">
</head>
<body>
    <!-- AFFICHAGE DE TOUTES LES INFOS -->
    <div class="information">
        <?php
            // AFFICHAGE DES DEVICES
            foreach ($cloud->getAllDevices() as $device)
            {
                $userName = $device->getUser() == null ? "NO USER" : $device->getUser()->getName();
                echo("<p> Device: " . $device->getName() . " ; Firmware: " . $device->getFirmwareVersion() . " ; User: " . $userName . "</p>");
            }
            // AFFICHAGE DES FIRMWARE VERSIONS
            echo("<p> All firmwares on this cloud: ");
            foreach ($cloud->getAllFirmwareVersions() as $firmwareVersion)
                echo($firmwareVersion . " ");
            echo("</p>");
        ?>
    </div>
</body>
</html>