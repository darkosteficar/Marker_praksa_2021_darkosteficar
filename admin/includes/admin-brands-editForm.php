<div class="card">
    <div class="card-body">

        <form action="" method="post" enctype="multipart/form-data" name="edit">
            <div class="form-group">
                <label for="category">Promjena imena branda</label>
                <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ime kategorije" value="<?php echo $brand['name'] ?>">
                <div class="valid-feedback">
                    Super!
                </div>
                <div class="invalid-feedback">
                    Molimo unesite ime branda.
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Opis branda</label>
                    <textarea name="description" class="form-control" id="post_content" rows="7" oninvalid="this.setCustomValidity('Unesite sadržaj objave!')" oninput="this.setCustomValidity('')"><?php echo $brand['description'] ?></textarea>
                    <div class="valid-feedback">
                        Super!
                    </div>
                    <div class="invalid-feedback">
                        Molimo unesite opis.
                    </div>
                </div>
            </div>
            <button type="submit" name="edit" class="btn btn-primary">Spremi</button>
        </form>
    </div>
</div>