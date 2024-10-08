<?php
include('partials/menu.php');
?>


<div class="main-content">
    <div class="wrapper">
        <h1>Add Category</h1>
        <br>
        <br>


        <?php
        if (isset($_SESSION['add'])) //checking wheather the session is set or not
        {
            echo $_SESSION['add']; //displaying session message
            unset($_SESSION['add']); //removing session message

        }

        if (isset($_SESSION['upload'])) //checking wheather the session is set or not
        {
            echo $_SESSION['upload']; //displaying session message
            unset($_SESSION['upload']); //removing session message

        }

        ?>

        <br>
        <br>


        <!--Add category  form start-->
        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">

                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" placeholder="Category Title">
                    </td>
                </tr>

                <tr>
                    <td>Select Image:</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>


                <tr>
                    <td>Featured:</td>
                    <td>
                        <input type="radio" name="featured" value="Yes">Yes
                        <input type="radio" name="featured" value="No">No
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes">Yes
                        <input type="radio" name="active" value="No">No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <br>
                        <input type="submit" name="submit" value="Add Category" class="btn-secondary">
                    </td>
                </tr>





            </table>
        </form>

        <!--Add category  form ends-->

        <?php
        //check wheather the submit button is clicked or not
        if (isset($_POST['submit'])) {
            //button clicked
            // echo "Button Clicked";

            //1. get  the Data from form
            $title = $_POST['title'];
            // $image_name = $_POST['image_name'];

            //for radio input ,we need to check wheather the button is clicked or not
            if (isset($_POST['featured'])) {
                //get the value from form
                $featured = $_POST['featured'];
            } else {
                //set the default value
                $featured = "No";
            }

            if (isset($_POST['active'])) {
                //get the value from form
                $active = $_POST['active'];
            } else {
                //set the default value
                $active = "No";
            }

            //print_r($_FILES['image']);
            //die(); //break the code here
            if (isset($_FILES['image']['name'])) {
                //upload the image
                //to upload image we needimage name, source path ,destination path
                $image_name = $_FILES['image']['name'];

                //Auto rename image
                //get the extension of image(jpg,png,gif,etc) e.g. "Specialfood1.jpg"
                $ext = end(explode('.', $image_name));

                //rename the image
                $image_name = "Food_Category_" . rand(000, 999) . '.' . $ext; //e.g .  Food_Category_834.jpg

                $source_path = $_FILES['image']['tmp_name'];
                $destination_path = "../images/category/" . $image_name;

                //finally upload image
                $upload = move_uploaded_file($source_path, $destination_path);

                //check wheather the Image is uploaded or not
                //And  if image not uploaded then we will stop the process and redirect with error message.
                if ($upload == false) {
                    //SET message
                    $_SESSION['upload'] = "<div class='error'>Failed to  upload image.</div> ";

                    header("location:" . SITEURL . 'admin/add-admin.php');
                    //stop the process
                    die();
                }
            } else {
                //Dont upload image and set the image_name As Blank.
                $image_name = "";
            }


            //2. sql query to save data /save category into database
            $sql = "INSERT INTO tbl_category SET
                title='$title',
                image_name='$image_name',
                featured='$featured',
                active='$active'
                ";

            //3. execute query and save data in database.
            $res = mysqli_query($conn, $sql) or die(mysqli_error($conn, $sql));

            if ($res == TRUE) {
                //DATA INSERTED
                echo "data inserted";
                //CREATE A SESSION VARIABLE TO DISPLAY MESSAGE
                $_SESSION['add'] = "<div class='success'> Category Added Successfully.</div>";
                //redirect page to manage admin
                header("location:" . SITEURL . 'admin/manage-category.php');
            } else {
                //FAIL TO DATA INSERTED
                //echo "FAIL TO INSERT DATA";
                //CREATE A SESSION VARIABLE TO DISPLAY MESSAGE
                $_SESSION['add'] = "<div class='error'>Failed to  Add Category.</div> ";
                //redirect page to add admin
                header("location:" . SITEURL . 'admin/add-category.php');
            }
        }
        ?>
    </div>
</div>




<?php include('partials/footer.php'); ?>