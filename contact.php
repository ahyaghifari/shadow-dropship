<title>Kontak | Shadow Dropship</title>
<?php 
    include "components/base.php";
    include "database/config.php";
    include "database/Contacts.php";    
    $contacts = new Contacts;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {  
        $contacts->createContact($conn, $_POST['name'], $_POST['message']);
    }
?>

<div id="contact" class="mt-20 p-3 flex flex-col md:flex-row w-full md:items-center min-h-[75vh]">
    <div id="contact-header" class="md:w-1/2 flex flex-col text-center justify-center items-center">
        <h1 class="text-3xl font-black sm:text-3xl md:text-5xl">Kontak</h1>
        <p class="text-[14px] text-neutral-400">Pesan yang kamu kirimkan hanya akan dibaca oleh superadmin</p>
        <?php if(isset($_SESSION['contact'])){?>
        <div id="message-contact" class="flex justify-between text-[12px] mt-2 w-full p-1 items-center sm:w-10/12 mx-auto <?= ($_SESSION['contact']['status'] == 200) ? "bg-green-600" : "bg-red-600" ?>">
            <p class="w-11/12"><?= $_SESSION['contact']['message'] ?></p>
        </div>
        <?php } ?>
    </div>
    <div id="contact-container" class="mt-10 md:mt-0 md:w-1/2">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="flex flex-col">
                <label for="name">Nama : </label>
                <input type="text" name="name" id="name" class="bg-transparent focus:outline-none border border-neutral-200 p-1 w-fit">
            </div>
            <div class="flex flex-col mt-2">
                <label for="message">Pesan : </label>
                <textarea name="message" id="message" cols="30" rows="10" class="bg-transparent focus:outline-none border border-neutral-200 p-1 w-10/12" required></textarea>
            </div>
            <div class="mt-5">
                <button type="submit" class="bg-neutral-200 text-neutral-800 p-2 font-black disabled:opacity-50" <?= (isset($_SESSION['contact'])) ? "disabled" : "" ?>>Kirim</button>
            </div>
        </form>
    </div>
</div>

<?php include "components/footer.php"?>
<?php include "components/script.php"?>
<?php include "components/foot.php"?>