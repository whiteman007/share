<?php
include "include/init.php";
/**
 * Created by KhalidBj.
 * Email: info@sp4dev.com
 * User: d34dlym4n
 * Date: 11/19/16
 * Time: 11:00 PM
 */
if (isset($_POST["set"])) {
    $doPost = $Share->security($_POST["set"]);

    switch ($doPost) {
        case "heartBeat";
            die(json_encode(array("status" => "alive")));
            break;
        case "login":
            header('content-type: application/json; charset=utf-8');
            $username = $Share->security($_POST["login_username"]);
            $password = $Share->security($_POST["login_password"]);

            echo json_encode(array("status" => ($Share->getLoginUser($username, md5($password)) ? "logged" : "wrong")));

            break;
        case "register":
            header('content-type: application/json; charset=utf-8');
            $username = $Share->security($_POST["reg_username"]);
            $password = $Share->security($_POST["reg_password"]);
            $email = $Share->security($_POST["reg_email"]);

            $regStatus = $Share->addNewUser($username, $password, $email, "user");

            echo json_encode(array("status" => ($regStatus > 0 ? "done" : $regStatus)));
            break;
        case "logout";
            session_destroy();
            break;
        case "newRequest":
            if ($Share->isLogged()) {
//                if ($Share->isAdmin()) {

                $addNew = $Share->addNewRequest(strip_tags($_POST["txt"]), $_SESSION['user_id']);

                header('Content-Type: application/json; charset=utf-8');
                die(json_encode(["status" => 'done', 'username' => $_SESSION['user_name'], 'userImg' => $_SESSION['user_image'], 'request_id' => $addNew, 'user_privilege' => $Share->isAdmin()]));
//                }
            }
            break;
        case "newReply":
            if ($Share->isLogged()) {
//                if ($Share->isAdmin()) {

                $id = $Share->security($_POST["id"]);

                $addNew = $Share->addNewRequestReply(strip_tags($_POST["txt"]), $id, $_SESSION['user_id']);

                header('Content-Type: application/json; charset=utf-8');
                die(json_encode(["status" => 'done', 'username' => $_SESSION['user_name'], 'userImg' => $_SESSION['user_image'], 'id' => $addNew, 'user_privilege' => $Share->isAdmin()]));
//                }
            }
            break;
        case "deleteRequest":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {

                    $id = $Share->security($_POST["id"]);
                    $type = $Share->security($_POST["type"]);

                    switch ($type) {
                        case "reply":
                            $Share->deleteReply($id);
                            break;
                        case "request":
                            $Share->deleteRequest($id);
                            break;
                    }


                    header('Content-Type: application/json; charset=utf-8');
                    die(json_encode(["status" => 'done']));
                }
            }
            break;
        case "completed":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {

                    $id = $Share->security($_POST["id"]);
                    $type = $Share->security($_POST["type"]);

                    switch ($type) {
                        case "reply":
                            $Share->updateReply($id);
                            break;
                        case "request":
                            $Share->updateRequest($id);
                            break;
                    }


                    header('Content-Type: application/json; charset=utf-8');
                    die(json_encode(["status" => 'done']));
                }
            }
            break;
        case "hasSeen":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {

                    $id = $Share->security($_POST["id"]);

                    $Share->updateCommentView($id);

                    header('Content-Type: application/json; charset=utf-8');
                    die(json_encode(["status" => 'done']));
                }
            }
            break;

        case "readAll":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
                    $notification = $Share->clearAllNotification();

                    header('Content-Type: application/json; charset=utf-8');
                    die(json_encode(array("status" => "done")));
                }
            }

            break;
        case "checkNotification":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
                    $notification = $Share->getNotificationList();

                    header('Content-Type: application/json; charset=utf-8');
                    die(json_encode(array("counts" => $notification['counts'])));
                }
            }

            break;
        case "search":
//            header('content-type: application/json; charset=utf-8');

            $searchTXT = (!empty($_POST['s'])) ? strtolower($Share->security($_POST['s'])) : null;
            $cat = (!empty($_POST['cat'])) ? strtolower($Share->security($_POST['cat'])) : null;

            $status = true;

            $data = $Share->searchPosts($searchTXT, $cat);

            $search = '';

            if ($data) {
                foreach ($data as $post) {

                    if (strlen($post['post_image']) <= 0) {
                        $post['post_image'] = "/assets/img/default.png";
                    }

                    if (!file_exists(UPLOAD_TOPIC_IMAGE . $post['post_image'])) {
                        $post['post_image'] = "/assets/img/default.png";
                    } else {
                        $post['post_image'] = "/upload/images/topic/" . $post['post_image'];
                    }

                    $search .= '<li>
                                <a href="/post/' . $post['post_id'] . '">
                                <img width="613" height="500" src="' . $post['post_image'] . '" class="" alt=""/>' . $post['post_title'] . '</a>
                                </li>';
                }
            }

            echo $search;

            break;
        case "getCategory":
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($Share->getAllCategory());
            break;

        case "addUser":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
                    header('content-type: application/json; charset=utf-8');
                    $username = $Share->security($_POST["reg_username"]);
                    $password = $Share->security($_POST["reg_password"]);
                    $email = $Share->security($_POST["reg_email"]);
                    $user_privilege = $Share->security($_POST["user_privilege"]);

                    $regStatus = $Share->addNewUser($username, $password, $email, $user_privilege);

                    die(json_encode(array("status" => ($regStatus > 0 ? "done" : $regStatus))));
                }
            }
            break;
        case "updateUsers":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
                    header('content-type: application/json; charset=utf-8');

                    $userID = $Share->security($_POST["userID"]);


                    $user_privilege = $Share->security($_POST["user_privilege"]);
                    $email = $Share->security($_POST["user_email"]);

                    $regStatus = $Share->updateUser($userID, $email, $user_privilege);

                    die(json_encode(array("status" => ($regStatus > 0 ? "done" : $regStatus))));
                }
            }
            break;
        case "deleteUser":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
                    $UserID = $Share->security($_POST["UserID"]);

                    $retData = $Share->deleteUser($UserID);

                    echo($retData > 0 ? "done" : $retData);
                }
            }
            break;
        case "changePassword":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
                    $UserID = $Share->security($_POST["UserID"]);
                    $newPw = $Share->security($_POST["newPw"]);
                    echo($Share->changePassword($UserID, $newPw) > 0 ? "done" : "error");
                }
            }
            break;
        case "addNewComment":
            if ($Share->isLogged()) {
                header('content-type: application/json; charset=utf-8');

                $userID = $_SESSION["user_id"];
                $comment = $Share->security($_POST["comment"]);
                $postID = $Share->security($_POST["postid"]);
                $commentDate = date("Y-m-d H:i:s");
                $commentData = 0;

                $userDetails = $Share->getUserByID($userID);

                if ($comment != "") {
                    $commentData = $Share->addNewComment($userID, $postID, $comment, $commentDate);
                } else {
                    echo json_encode(array(
                        "status" => false
                    ));
                }

                if ($commentData > 0) {
                    echo json_encode(array(
                        "status" => true,
                        "comment_id" => $commentData,
                        "user_image" => (strlen($userDetails["user_image"]) >= 0 ? "default.png" : $userDetails["user_image"]),
                        "user_name" => $userDetails["user_name"],
                        "comment_date" => $commentDate
                    ));
                }

            }
            break;
        case "likePost";
            if ($Share->isLogged()) {
                $postID = $Share->security($_POST["postID"]);
//                $likeOPT = $Share->security($_POST["like"]);
                $userID = $_SESSION["user_id"];

                echo json_encode(array(
                    "status" => $Share->likePost($postID, $userID)
                ));
            } else {
                echo json_encode(array(
                    "status" => 'login'
                ));
            }
            break;

        case "updatePost":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
                    $postID = $Share->security($_POST["postid"]);
                    $editTitle = strip_tags($_POST["editTitle"]);
                    $editCategory = $Share->security($_POST["editCategory"]);
                    $editDesc = $Share->security($_POST["editDesc"]);

                    if (($_FILES) || (!empty($_FILES['imagePoster']))) {
                        $post = $Share->getPostBy($postID);
                        $up_image = $_FILES['imagePoster'];
                        $ret = '';

                        $fileName = $up_image["name"];
                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $fileTmp = $up_image["tmp_name"];

                        if (!$Share->checkIsAccepted($fileName, $fileTmp, $bExt, $bMime)) {
                            $ret = "forbidden";
                            $fileName = $fileTmp = null;
                            break;
                        }

                        $postFile = $Share->uploadFile($fileName, $fileTmp, UPLOAD_FILES_DIR);

                        if ($postFile["status"]) {

                            array_push($uploadedFile, $postFile);

                            $imageExtension = pathinfo($up_image["name"], PATHINFO_EXTENSION);

                            if ($Share->checkIsAccepted($up_image["name"], $up_image["tmp_name"], $bExt, $bMime)) {


                                if (strlen($ret) <= 0) {
                                    $Share->removeFile($postID, 'poster');

                                    $imageFile = $Share->uploadFile($up_image["name"], $up_image["tmp_name"], UPLOAD_TOPIC_IMAGE, 'image');
                                    $Share->smart_resize_image(UPLOAD_TOPIC_IMAGE . $imageFile["name"], UPLOAD_TOPIC_IMAGE . $imageFile["name"], 450, 600, false);

                                    $Share->updateMovieBySchema("post_image", $imageFile['name'], $postID);

                                    die(json_encode(array("status" => "done")));
                                }
                            } else {
                                $ret = "forbidden";
                                $uploadedFiles = $fileName = $fileTmp = null;
                            }

                        } else {
                            die(json_encode(array("ST" => $ret)));
                        }


                    }
                    die(json_encode(array("status" => $Share->updatePost($postID, $editTitle, $editCategory, $editDesc) > 0 ? "done" : "error")));

                }
            }
            break;

        case "deleteComment";
            if ($Share->isLogged()) {
                $commentID = $Share->security($_POST["commentID"]);
                echo($Share->deleteComment($commentID) > 0 ? "done" : "error");
            }
            break;

        case "getListItems":
            if ($Share->isLogged()) {
                header('Content-Type: application/json; charset=utf-8');

                $categoryID = intval($Share->security($_POST['categoryID']));

                die(json_encode($Share->getPostByCategoryID($categoryID)));
            }
            break;

        case "uploadFilesData";
            if ($Share->isLogged()) {
                header('content-type: application/json; charset=utf-8');

                $up_title = $Share->security($_POST["up_title"]);
                $up_category = $Share->security($_POST["up_category"]);
                $up_desc = $Share->security($_POST["up_desc"]);
                $up_by = $_SESSION["user_id"];

                $up_image = $_FILES["up_image"];
                $up_file = $_FILES["up_file"];

                $ret = null;
                $uploadedFile = [];

                $fileName = $up_file["name"];
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $fileTmp = $up_file["tmp_name"];

                if (!$Share->checkIsAccepted($fileName, $fileTmp, $bExt, $bMime)) {
                    $ret = "forbidden";
                    $fileName = $fileTmp = null;
                    break;
                }

                $uploadFilesDir = $selectedDisk['mount'] . DIRECTORY_SEPARATOR . UPLOAD_FILES_DIR . DIRECTORY_SEPARATOR;
                $uploadImagesDir = $selectedDisk['mount'] . DIRECTORY_SEPARATOR . UPLOAD_TOPIC_IMAGE . DIRECTORY_SEPARATOR;

                $postFile = $Share->uploadFile($fileName, $fileTmp, $uploadFilesDir);

                if ($postFile["status"]) {

                    array_push($uploadedFile, $postFile);

                    $imageExtension = pathinfo($up_image["name"], PATHINFO_EXTENSION);

                    if ($Share->checkIsAccepted($up_image["name"], $up_image["tmp_name"], $bExt, $bMime)) {

                        $imageFile = $Share->uploadFile($up_image["name"], $up_image["tmp_name"], $uploadImagesDir, 'image');
                        $Share->smart_resize_image($uploadImagesDir . $imageFile["name"], $uploadImagesDir . $imageFile["name"], 450, 600, false);

                        if (strlen($ret) <= 0) {
                            $ret = ($Share->addNewFile($up_title,
                                $imageFile["name"],
                                json_encode($uploadedFile),
                                $Share->human_file_size($postFile['size']),
                                $selectedDisk['uuid'],
                                $up_category,
                                $up_desc,
                                $up_by) > 0 ? "done" : "error");

                        }
                    } else {
                        $ret = "forbidden";
                        $uploadedFiles = $fileName = $fileTmp = null;
                    }

                }

                echo json_encode(array("ST" => $ret));

            }
            break;

        case "uploadFileParts";

            if ($Share->isLogged()) {
                header('content-type: application/json; charset=utf-8');

                $part_category = $Share->security($_POST["part_category"]);
                $part_items = $Share->security($_POST["part_items"]);

                $part_file = $_FILES["part_file"];

                $ret = null;

                $fileName = $part_file["name"];
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $fileTmp = $part_file["tmp_name"];

                if (!$Share->checkIsAccepted($fileName, $fileTmp, $bExt, $bMime)) {
                    $ret = "forbidden";
                    $fileName = $fileTmp = null;
                    echo json_encode(array("ST" => $ret));
                    break;
                }

                $uploadFilesDir = $selectedDisk['mount'] . DIRECTORY_SEPARATOR . UPLOAD_FILES_DIR . DIRECTORY_SEPARATOR;
//                $uploadImagesDir = $selectedDisk['mount'] . DIRECTORY_SEPARATOR . UPLOAD_TOPIC_IMAGE . DIRECTORY_SEPARATOR;

                $postFile = $Share->uploadFile($fileName, $fileTmp, $uploadFilesDir);


                if ($postFile['status']) {
                    $ret = ($Share->addNewPart($part_items, $postFile) > 0 ? "done" : "error");
                }


                echo json_encode(array("ST" => $ret));

            }
            break;
        case "AllUsersList":
            header('Content-Type: application/json; charset=utf-8');
            //header('Content-Type: application/json; charset=utf-8');
            $draw = $Share->security($_POST['draw']);
            $start = $Share->security($_POST['start']);
            $length = $Share->security($_POST['length']);

            $search = $Share->security($_POST['search']['value']);
            // index of the sorting column (0 index based - i.e. 0 is the first record)
            $orderByColumnIndex = $Share->security($_POST['order'][0]['column']);
            //Get name of the sorting column from its index
            $orderBy = $Share->security($_POST['columns'][$orderByColumnIndex]['data']);
            // ASC or DESC
            $orderType = $Share->security($_POST['order'][0]['dir']);


            $data = $Share->getAllUsers($draw, $start, $length, $orderBy, $orderType, $search);

            echo json_encode($data);
            break;
        case "AllPostsList":
            header('Content-Type: application/json; charset=utf-8');
            //header('Content-Type: application/json; charset=utf-8');
            $draw = $Share->security($_POST['draw']);
            $start = $Share->security($_POST['start']);
            $length = $Share->security($_POST['length']);

            $search = $Share->security($_POST['search']['value']);
            // index of the sorting column (0 index based - i.e. 0 is the first record)
            $orderByColumnIndex = $Share->security($_POST['order'][0]['column']);
            //Get name of the sorting column from its index
            $orderBy = $Share->security($_POST['columns'][$orderByColumnIndex]['data']);
            // ASC or DESC
            $orderType = $Share->security($_POST['order'][0]['dir']);

            $data = $Share->getAllPosts($draw, $start, $length, $orderBy, $orderType, $search, 1);
            echo json_encode($data);
            break;
        case "AllNewPostsList":
            header('Content-Type: application/json; charset=utf-8');
            //header('Content-Type: application/json; charset=utf-8');
            $draw = $Share->security($_POST['draw']);
            $start = $Share->security($_POST['start']);
            $length = $Share->security($_POST['length']);

            $search = $Share->security($_POST['search']['value']);
            // index of the sorting column (0 index based - i.e. 0 is the first record)
            $orderByColumnIndex = $Share->security($_POST['order'][0]['column']);
            //Get name of the sorting column from its index
            $orderBy = $Share->security($_POST['columns'][$orderByColumnIndex]['data']);
            // ASC or DESC
            $orderType = $Share->security($_POST['order'][0]['dir']);


            $data = $Share->getAllPosts($draw, $start, $length, $orderBy, $orderType, $search, 0);
            echo json_encode($data);
            break;
        case "AllMCatList":
            header('Content-Type: application/json; charset=utf-8');
            $data = $Share->getAllCategoryList();
            echo json_encode($data);
            break;
        case "AllSCatList":
            header('Content-Type: application/json; charset=utf-8');
            $data = $Share->getAllSCategoryList();
            echo json_encode($data);
            break;
        case "editSubCategory":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
                    header('Content-Type: application/json; charset=utf-8');
                    $categoryID = $Share->security($_POST["categoryID"]);
                    $category_name = $Share->security($_POST["editTitle"]);
                    $editCategory = isset($_POST["editCategory"]) ? $Share->security($_POST["editCategory"]) : 0;

                    die(json_encode(array("status" => $Share->updateSCategory($categoryID, $category_name, $editCategory) > 0 ? "done" : "error")));
                }
            }

            break;
        case "editCategory":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
                    header('Content-Type: application/json; charset=utf-8');
                    $categoryID = $Share->security($_POST["categoryID"]);
                    $category_name = $Share->security($_POST["editTitle"]);
                    $editCategory = isset($_POST["editCategory"]) ? $Share->security($_POST["editCategory"]) : 0;

                    die(json_encode(array("status" => $Share->updateSCategory($categoryID, $category_name, $editCategory) > 0 ? "done" : "error")));
                }
            }

            break;
        case "deletePost":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
//                    header('Content-Type: application/json; charset=utf-8');
                    $postID = $Share->security($_POST["postID"]);
                    echo($Share->deletePost($postID) > 0 ? "done" : "error");
                }
            }

            break;
        case "deletePart":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
//                    header('Content-Type: application/json; charset=utf-8');
                    $parent = $Share->security($_POST["parent"]);
                    $part = $Share->security($_POST["part"]);

                    echo($Share->deletePart($parent, $part) > 0 ? "done" : "error");
                }
            }

            break;
        case "disablePost":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
//                    header('Content-Type: application/json; charset=utf-8');
                    $postID = $Share->security($_POST["postID"]);
                    echo($Share->changePostStatus($postID, 0) > 0 ? "done" : "error");
                }
            }

            break;
        case "enablePost":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
//                    header('Content-Type: application/json; charset=utf-8');
                    $postID = $Share->security($_POST["postID"]);
                    echo($Share->changePostStatus($postID, 1) > 0 ? "done" : "error");
                }
            }

            break;
        case "addNewCategory":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
                    header('Content-Type: application/json; charset=utf-8');
                    $category_name = $Share->security($_POST["category_name"]);
                    $category_parent = $Share->security($_POST["category_parent"]);
                    $category_main = $Share->security($_POST["category_main"]);

                    echo json_encode(array("status" => $Share->addNewCategory($category_name, $category_main) > 0 ? "done" : "error"));
                }
            }

            break;
        case "deleteCategory":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
                    header('Content-Type: application/json; charset=utf-8');
                    $MCatID = $Share->security($_POST["MCatID"]);

                    $retSend = $Share->deleteCategory($MCatID);
                    echo json_encode(array("status" => $retSend > 0 ? "done" : $retSend));
                }
            }

            break;
        case "setSettings":
            if ($Share->isLogged()) {
                if ($Share->isAdmin()) {
                    header('Content-Type: application/json; charset=utf-8');

                    $siteName = $Share->security($_POST["siteName"]);
                    $siteUrl = $Share->security($_POST["siteUrl"]);
                    $Items = $Share->security($_POST["Items"]);
                    $disk = $Share->security($_POST["sysDisk"]);
                    $logo = isset($_FILES["logo"]) ? $_FILES["logo"] : "";

                    $ar = array(
                        'SITE_NAME' => $siteName,
                        'SITE_URL' => $siteUrl,
                        'ROW_PER_PAGE' => $Items,
                        'SELECT_DISK' => $disk,
                    );

                    if (is_array($logo)) {
                        $logoFile = $Share->uploadFile($logo["name"], $logo["tmp_name"], IMAGE_SYSTEM, 'image');
                        if ($logoFile["status"]) {
                            $Share->smart_resize_image(IMAGE_SYSTEM . $logoFile["name"], IMAGE_SYSTEM . $logoFile["name"], 195, 74, false);
                            @unlink(IMAGE_SYSTEM . $Share->getSetting("SITE_LOGO"));
                            $ar["SITE_LOGO"] = $logoFile["name"];
                        }
                    }

                    $retSend = $Share->UpdateSetting($ar);
                    echo json_encode(array("status" => $retSend > 0 ? "done" : $retSend));
                }
            }

            break;
        case "getStatics";
            header('Content-Type: application/json; charset=utf-8');
            die(json_encode($Share->getData($Share->security($_POST['date']))));
            break;
    }
}
