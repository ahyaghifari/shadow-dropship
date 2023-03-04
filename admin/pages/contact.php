<?php 
$max_date = date('Y-m-d');
$name_filter = "";
$status_filter = "";
$sort_by_filter = "";
$from_filter = "";
$to_filter = "";

if(isset($_GET['name']) && isset($_GET['status']) && isset($_GET['sort_by']) && isset($_GET['from']) && isset($_GET['to'])){
    $allContacts = $contacts->allContacts($conn, $_GET['name'], $_GET['status'], $_GET['sort_by'], $_GET['from'], $_GET['to']);
    $name_filter = $_GET['name'];
    $status_filter = $_GET['status'];
    $sort_by_filter = $_GET['sort_by'];
    $from_filter = $_GET['from']; 
    $to_filter = $_GET['to']; 
}else{
    $allContacts = $contacts->allContacts($conn, "", "", "", "", "");
}
?>
<div id="contact">
    <div id="contact-header" class="border-b border-neutral-800 pb-2 border-dashed flex justify-between items-stretch">
        <h1 class="text-xl sm:text-2xl md:text-3xl">Kontak</h1>

    </div>
    <div id="contact-filter">
        <form action="index.php" method="get" class="form-contact-filter clear-both text-[12px] grid grid-cols-2  gap-x-3 gap-y-3 sm:text-[13px] md:text-sm lg:text-base md:grid-cols-3 lg:grid-cols-4 items-end md:gap-x-5 md:gap-y-2">
            <input type="hidden" name="pages" value="contact">
            <input type="text" name="name" value="<?= $name_filter ?>" placeholder="Nama...">
            <div class="flex flex-col">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="">-----</option>
                    <option value="belum-dibaca" <?= ($status_filter == "belum-dibaca") ? "selected" : "" ?>>Belum Dibaca</option>
                    <option value="sudah-dibaca" <?= ($status_filter == "sudah-dibaca") ? "selected" : "" ?>>Sudah Dibaca</option>
                </select>
            </div>
            <div class="flex flex-col">
                <label for="sort-by">Urutkan</label>
                <select name="sort_by" id="sort-by">
                    <option value="">-----</option>
                    <option value="newest" <?= ($sort_by_filter == "newest") ? "selected" : "" ?>>Paling Baru</option>
                    <option value="oldest" <?= ($sort_by_filter == "oldest") ? "selected" : "" ?>>Paling Lama</option>
                </select>
            </div>
            <div class="flex flex-col col-start-1"> 
                <label for="from">Dari</label>
                <input type="date" name="from" id="from" min="2023-03-01" max="<?= $max_date ?>" value="<?= $from_filter ?>">
            </div>
            <div class="flex flex-col"> 
                <label for="to">Ke</label>
                <input type="date" name="to" id="to" max="<?= $max_date ?>" value="<?= $to_filter ?>">
            </div>
            <div class="col-start-1 lg:col-start-4 h-full flex items-center">
                <button type="submit" class="bg-neutral-700 p-1 px-3 text-neutral-200">Cari</button>
                <a href="index.php?pages=contact" class="bg-neutral-300 p-1 px-3 text-neutral-700 ml-3">Reset </a>
            </div>
        </form>
    </div>
    <div id="contact-container" class="clear-both mt-5 h-screen overflow-y-scroll pr-2 border-y border-neutral-400 py-2">
        <?php if($allContacts > 0){ foreach($allContacts as $contacs){?>
        <div class="contacts border border-neutral-700 rounded-md mb-3 shadow-sm sm:w-11/12 mx-auto md:w-9/12 md:mb-5">
            <div class="contacts-sender p-2 grid grid-cols-3 text-[11px] text-center sm:text-[12px] md:text-[13px]">
                <h3 class="text-[13px] md:text-sm"><?= ($contacs->name != NULL) ? $contacs->name : "Tidak ada nama" ?></h3>
                <h3 class="to-calendar"><?= $contacs->created_at ?></h3>
                <h3 id="contacts-readed-<?= $contacs->id ?>" class="contacts-readed"><?= ($contacs->readed == false ) ? "Belum dibaca" : "Sudah dibaca" ?></h3>
            </div>
            <div id="contacts-message" class="contacts-messages text-[12px] p-2 sm:text-[13px] md:text-[15px]">
                <p class="border-y border-neutral-400 py-2"><?= $contacs->message ?></p>
                <div class="mt-4 flex item-center">
                    <label for="readed">Dibaca : </label>
                    <input type="checkbox" name="readed" class="input-contacts-read ml-2" data-id="<?= $contacs->id ?>" <?= ($contacs->readed == true) ? "checked" : "" ?>>
                </div>
            </div>
            <button id="contact-message-btn-<?= $contacs->id ?>" class="contact_expand-btn w-full text-neutral-700 <?= ($contacs->readed == false) ? "bg-neutral-400" : "bg-neutral-300" ?>"><span class="material-symbols-rounded text-2xl text-neutral-500">mail</span></button>
        </div>
        <?php }}else{?>
            <p class="text-center text-sm">Kontak tidak ditemukan</p>
        <?php } ?>
        
    </div>
</div>