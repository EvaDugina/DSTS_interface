<?php
require_once("utilities.php");
require_once("common.php");

$au = new auth_ssh();

checkAuLoggedIN($au);
// checkAuIsAdmin($au);

if (isset($_GET['article_id']))
    $article_id = $_GET['article_id'];
else
    exit;


if ($au->isAdmin())
    echo "<script>var is_admin=1;</script>";
else
    echo "<script>var is_admin=0;</script>";


$Article = new Article($article_id);
$imageUrls = $Article->getImageUrls();

$group_ids = $Article->getGroups();

$mainAnalogs = getMainArticleAnalogs($Article, $group_ids);
$allAnalogs = getAllArticleAnalogs($Article, $group_ids);

show_head("СТРАНИЦА ИНФОРМАЦИИ О ТОВАРЕ");
?>

<body style="overflow-x: hidden;">
    <?php show_header("ИНФОРМАЦИЯ О ТОВАРЕ: " . $Article->name); ?>
    <main class="">

        <div class="pt-5 px-4">
            <div class="row">
                <div class="px-5 d-flex">
                    <div class="col-md-3 me-4">
                        <?php if (count($imageUrls) > 0) { ?>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="embed-responsive embed-responsive-1by1 text-center">
                                        <div class="embed-responsive-item">
                                            <img class="w-100 h-100 p-0 m-0 border rounded" src="<?= $imageUrls[0] ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <!-- <svg class="w-100 h-auto mb-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                            </svg> -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-100 h-auto mb-4 bi bi-x border rounded" viewBox="0 0 16 16">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                            </svg>
                        <?php } ?>

                        <?php if (false) { ?>
                            <form id="form-EditImage" name="image" action="profile_edit.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="set-image" value="true"></input>
                                <label class="btn btn-outline-primary py-2 px-4">
                                    <input id="input-image" type="file" name="image-file" style="display: none;">
                                    &nbsp; <?= ($Article->getImageUrls() != null) ? 'Изменить фотографию' : 'Добавить фотографию' ?>
                                </label>
                            </form>
                        <?php } ?>

                        <?php foreach ($ARRAY_CATALOGUES as $key => $catalogue) {
                            if ($Article->hasInfo($catalogue["name"])) { ?>
                                <button class="btn btn-primary mb-2 w-100 bg-<?= $catalogue["backgroundColor"] ?> text-<?= $catalogue["frontColor"] ?>" onclick="goToCataloguePage('<?= $Article->getLinkToCataloguePage($catalogue['name']) ?>', '<?= $catalogue['name'] ?>')">
                                    ПЕРЕЙТИ НА САЙТ <strong><?= $catalogue["name"] ?></strong>
                                </button>
                        <?php }
                        } ?>

                    </div>

                    <div clacc="col-4" style="width:inherit;">

                        <div>
                            <?php if ($Article->hasInfo()) {
                                $characteristics = $Article->getAllCharacteristics(); ?>
                                <table class="table border rounded mx-0" style="border-spacing: 0; border-collapse: separate;">
                                    <thead class="px-0">
                                        <tr class="border bg-primary text-white">
                                            <th scope="col" class="middleInTable border fw-bold">
                                                <div class="d-inline-flex justify-content-between align-items-center">
                                                    <span>ХАРАКТЕРИСТИКИ</span>
                                                    <?php if ($au->isAdmin() && count($characteristics) > 0) { ?>
                                                        <button class="badge badge-light badge-pill border-0 ms-3" onclick="editCharacteristicList()">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                                            </svg>
                                                        </button>
                                                    <?php } ?>
                                                </div>
                                            </th>
                                            <?php foreach ($Article->getMainInfo() as $info_by_catalogue) { ?>
                                                <th scope="col" class="middleInTable border fw-bold"><?= $info_by_catalogue['catalogue_name'] ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody id="" role="button" class="px-0" style="cursor:auto; border: transparent;">
                                        <?php if (count($characteristics) > 0) {
                                            foreach ($characteristics as $line) { ?>
                                                <tr class="border">
                                                    <td scope="row" class="middleInTable border fw-bold"><?= $line['alt_name'] ?></td>
                                                    <?php foreach ($line['characteristic'] as $characteristic_by_catalogue) {
                                                        if (str_contains($characteristic_by_catalogue, "inch)") || str_contains($characteristic_by_catalogue, "psi)"))
                                                            $characteristic_by_catalogue = explode("(", $characteristic_by_catalogue)[0];
                                                    ?>
                                                        <td class="middleInTable border"><?= $characteristic_by_catalogue ?></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr class="border">
                                                <td scope="row" class="middleInTable border fw-bold">ИНФОРМАЦИЯ ОТСУТСТВУЕТ</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            <?php } else { ?>
                                <h6>Информация о характеристиках отсутствует</h6>
                            <?php } ?>
                        </div>

                    </div>

                </div>
            </div>

            <div id="div-main-analogs" class="mb-2">
                <table id="table-main-analogs" class="table border rounded mx-0" style="border-spacing: 0; border-collapse: separate;">
                    <thead class="px-0">
                        <tr class="bg-info text-white">
                            <th class="middleInTable col-1"><strong>АРТИКУЛ</strong></th>
                            <th class="middleInTable col-2" style="white-space: nowrap;"><strong>ПРОИЗВОДИТЕЛЬ ПО DSTS</strong></th>
                            <?php if ($au->isAdmin()) { ?>
                                <th class="middleInTable col-3" style="white-space: nowrap;"><strong>НАЗВАНИЕ КАТАЛОГА</strong></th>
                                <th class="middleInTable col-4"><strong>ПРОИЗВОДИТЕЛЬ</strong></th>
                                <th class="middleInTable col-1"></th>
                                <th class="middleInTable col-1"></th>
                            <?php } else { ?>
                                <th class="middleInTable col-6"><strong>ОПИСАНИЕ</strong></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody id="tbody-main-analogs" role="button" class="px-0" style="border: transparent;">
                        <?php
                        $index = 0;
                        foreach ($mainAnalogs as $analog) {
                            if ($index < $COUNT_LOADING_ELEMENTS) { ?>
                                <tr class="border">
                                    <td class="middleInTable col-1 cursor-auto">
                                        <button class="btn btn-link <?= ($analog['hasInfo']) ? 'text-success' : '' ?>" onclick="goToArticleDetails(<?= $analog['article_id'] ?>)" style="font-size:inherit;"><?= $analog['article_name'] ?></button>
                                    </td>
                                    <td class="middleInTable col-2 cursor-auto <?= ($analog['producer_name_dsts'] == "") ? 'text-danger' : '' ?>">
                                        <?= ($analog['producer_name_dsts'] == "") ? $analog['producer_name'] : $analog['producer_name_dsts'] ?>
                                    </td>
                                    <td class="middleInTable col-3 cursor-auto">
                                        <?= $analog['catalogue_name'] ?>
                                    </td>
                                    <?php if ($au->isAdmin()) { ?>
                                        <td class="middleInTable col-3 cursor-auto">
                                            <span>
                                                <?= $analog['producer_name_by_catalogue'] ?> (<strong style="font-weight:bold;"><?= $analog['producer_name'] ?></strong>)
                                            </span>
                                        </td>
                                        <td class="middleInTable col-1 cursor-auto">
                                            <button class="badge badge-primary badge-pill" style="border: unset;" onclick='clickToButtonEditLine(JSON.parse("<?= addslashes(json_encode($analog)) ?>"))'>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                                                    <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001z" />
                                                </svg>
                                            </button>
                                        </td>
                                        <td class="middleInTable col-1 cursor-auto">
                                            <button class="badge <?= ($analog['hasInfo']) ? 'badge-success' : 'd-none' ?> badge-pill" style="border: unset;" onclick='clickButtonCompare()'>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-data-fill" viewBox="0 0 16 16">
                                                    <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5" />
                                                    <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585q.084.236.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5q.001-.264.085-.5M10 7a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0zm-6 4a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0zm4-3a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0V9a1 1 0 0 1 1-1" />
                                                </svg>
                                            </button>
                                        </td>
                                    <?php } else { ?>
                                        <td class="middleInTable col-6 cursor-auto">
                                            <?= ($analog['description'] != "") ? $analog['description'] : '(тип неопределён)' ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                        <?php
                                $index++;
                            }
                        } ?>
                    </tbody>
                </table>
                </br>
            </div>

            <div id="div-all-analogs" class="mt-3 mb-5">
                <table id="table-analogs" class="table border rounded mx-0" style="border-spacing: 0; border-collapse: separate;">
                    <thead class="px-0">
                        <tr class="table-active">
                            <th class="middleInTable col-2"><strong>АРТИКУЛ</strong></th>
                            <th class="middleInTable col-2" style="white-space: nowrap;"><strong>ПРОИЗВОДИТЕЛЬ ПО DSTS</strong></th>
                            <?php if ($au->isAdmin()) { ?>
                                <th class="middleInTable col-3" style="white-space: nowrap;"><strong>НАЗВАНИЕ КАТАЛОГА</strong></th>
                                <th class="middleInTable col-4"><strong>ПРОИЗВОДИТЕЛЬ</strong></th>
                                <th class="middleInTable col-1"></th>
                            <?php } else { ?>
                                <th class="middleInTable col-6"><strong>ОПИСАНИЕ</strong></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody id="tbody-analogs" role="button" class="px-0" style="border: transparent;">
                        <?php
                        $index = 0;
                        foreach ($allAnalogs as $analog) {
                            if ($index < $COUNT_LOADING_ELEMENTS) { ?>
                                <tr class="border">
                                    <td class="middleInTable col-2 cursor-auto">
                                        <button class="btn btn-link <?= ($analog['hasInfo']) ? 'text-success' : '' ?>" onclick="goToArticleDetails(<?= $analog['article_id'] ?>)" style="font-size:inherit;"><?= $analog['article_name'] ?></button>
                                    </td>
                                    <td class="middleInTable col-2 cursor-auto <?= ($analog['producer_name_dsts'] == "") ? 'text-danger' : '' ?>">
                                        <?= ($analog['producer_name_dsts'] == "") ? $analog['producer_name'] : $analog['producer_name_dsts'] ?>
                                    </td>
                                    <td class="middleInTable col-3 cursor-auto">
                                        <?= $analog['catalogue_name'] ?>
                                    </td>
                                    <?php if ($au->isAdmin()) { ?>
                                        <td class="middleInTable col-4 cursor-auto">
                                            <span>
                                                <?= $analog['producer_name_by_catalogue'] ?> (<strong style="font-weight:bold;"><?= $analog['producer_name'] ?></strong>)
                                            </span>
                                        </td>
                                        <td class="middleInTable col-1 cursor-auto">
                                            <button class="badge badge-primary badge-pill" style="border: unset;" onclick='clickToButtonEditLine(JSON.parse("<?= addslashes(json_encode($analog)) ?>"))'>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                                                    <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001z" />
                                                </svg>
                                            </button>
                                        </td>
                                    <?php } else { ?>
                                        <td class="middleInTable col-6 cursor-auto">
                                            <?= ($analog['description'] != "") ? $analog['description'] : '(тип неопределён)' ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                        <?php
                                $index++;
                            }
                        } ?>
                    </tbody>
                </table>

                <?php if ($index >= $COUNT_LOADING_ELEMENTS) { ?>
                    <div id="div-analogs-showMore" class="d-flex justify-content-center">
                        <button class="btn btn-outline-primary mt-1 mb-5" onclick='showMoreArticles(JSON.parse("<?= addslashes(json_encode($allAnalogs)) ?>"))'>
                            ПОКАЗАТЬ БОЛЬШЕ
                        </button>
                    </div>
                <?php } ?>

            </div>

        </div>
        </div>
    </main>
</body>


<div class="modal fade" id="dialogModalEditCharacteristics" tabindex="-1" aria-labelledby="dialogMarkLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modalEditCharacteristics-h5-title" class="modal-title">РЕДАКТИРОВАНИЕ НАЗВАНИЯ ХАРАКТЕРИСТИК</h5>
                <button type="button" class="btn-close me-2" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalEditCharacteristics-div-characteristics" class="d-flex-column">
                    <!-- <h6>Добавление артикула по каталогу:</h6> -->
                    <?php $index = 0;
                    foreach ($characteristics as $key => $line) { ?>
                        <div class="d-inline-flex align-items-center w-100 mb-2">
                            <input id="modalEditCharacteristics-input-realCharacteristicName-<?= $index ?>" type="text" value="<?= $line['alt_name'] ?>" class="form-control w-100 my-0" readonly>
                            <div class="mx-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                                </svg>
                            </div>
                            <input id="modalEditCharacteristics-input-newCharacteristicName-<?= $index ?>" type="text" value="" class="form-control w-100 my-0" placeholder="Введите название характеристики">
                        </div>
                    <?php $index += 1;
                    } ?>
                    <!-- <p id="modalEditCharacteristics-p-inputError" class="text-danger d-none">
                        <small><strong>ВНИМАНИЕ! В строке не должно присутсвовать ничего, кроме слитно написанного названия артикула</strong></small>
                    </p> -->
                </div>
                <br />
            </div>
            <div class="modal-footer">
                <button id="modalEditCharacteristics-button-apply" type="button" class="btn btn-primary align-items-center">
                    ПРИМЕНИТЬ
                    <div id="modalEditCharacteristics-spinner-waiting" class="spinner-border spinner-border-sm text-white ms-2 float-end d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="dialogModalCompareCharacteristics" tabindex="-1" aria-labelledby="dialogMarkLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modalCompareCharacteristics-h5-title" class="modal-title">РЕДАКТИРОВАНИЕ НАЗВАНИЯ ХАРАКТЕРИСТИК</h5>
                <button type="button" class="btn-close me-2" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalCompareCharacteristics-div-characteristics" class="d-flex-column">
                    <!-- <h6>Добавление артикула по каталогу:</h6> -->
                    <?php $index = 0;
                    foreach ($characteristics as $key => $line) { ?>
                        <div class="d-inline-flex align-items-center w-100 mb-2">
                            <input id="modalCompareCharacteristics-input-realCharacteristicName-<?= $index ?>" type="text" value="<?= $line['alt_name'] ?>" class="form-control w-100 my-0" readonly>
                            <div class="mx-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                                </svg>
                            </div>
                            <input id="modalCompareCharacteristics-input-newCharacteristicName-<?= $index ?>" type="text" value="" class="form-control w-100 my-0" placeholder="Введите название характеристики">
                        </div>
                    <?php $index += 1;
                    } ?>
                    <!-- <p id="modalEditCharacteristics-p-inputError" class="text-danger d-none">
                        <small><strong>ВНИМАНИЕ! В строке не должно присутсвовать ничего, кроме слитно написанного названия артикула</strong></small>
                    </p> -->
                </div>
                <br />
            </div>
            <div class="modal-footer">
                <button id="modalCompareCharacteristics-button-apply" type="button" class="btn btn-primary align-items-center">
                    ПРИМЕНИТЬ
                    <div id="modalCompareCharacteristics-spinner-waiting" class="spinner-border spinner-border-sm text-white ms-2 float-end d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<?php showSearchPopovers(); ?>



<script type="text/javascript">
    function goToCataloguePage(link, catalogue_name) {
        window.open(link, '_blank');
    }

    function clickButtonCompare() {

    }

    function editCharacteristicList() {
        // let characteristics = [];
        // $('#tbody-article').each((index, tr) => {
        //     let characteristic = tr.children[0].innerText;
        //     characteristics.push(characteristic);
        // });
        $('#dialogModalEditCharacteristics').modal('show');
    }

    $('#modalEditCharacteristics-button-apply').on("click", function(event) {
        let characteristics = [];
        $('#modalEditCharacteristics-div-characteristics').children().each((index, div) => {
            let realName = div.children[0].value;
            let newName = div.children[2].value;
            if (newName != "") {
                characteristics.push({
                    "realName": realName,
                    "newName": newName
                });
            }
        });
        ajaxEditCharacteristics(characteristics);
        $('#dialogModalEditCharacteristics').hide();
    });

    function ajaxEditCharacteristics(characteristics) {
        var formData = new FormData();

        formData.append('editCharacteristics', true);
        formData.append('characteristics', JSON.stringify(characteristics));

        $.ajax({
            type: "POST",
            url: 'edit_action.php#content',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            dataType: 'html',
            success: function(response) {
                // response = JSON.parse(response);
                // console.log(response);
                location.reload();
            },
            complete: function() {}
        });
    }

    function ajaxGetArticleCharacteristics(article_id) {
        var formData = new FormData();

        formData.append('getCharacteristics', true);
        formData.append('article_id', article_id);

        $.ajax({
            type: "POST",
            url: 'edit_action.php#content',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            dataType: 'html',
            success: function(response) {

            },
            complete: function() {}
        });
    }
</script>

<script type="text/javascript" src="js/TableHandler.js"></script>