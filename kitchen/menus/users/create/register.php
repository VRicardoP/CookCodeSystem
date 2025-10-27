

   
        <div id="content">
            <div class="container-fluid text-center align-center">
                <h3><strong><?= $title ?></strong></h3>
                <form id="registro" enctype="multipart/form-data">
                    <div class="shadow-lg border rounded row justify-content-center m-5 p-3">
                        <div class="col-12 col-md-6 text-left">
                            <input type="hidden" name="user_id" id="user_id" value="<?= htmlspecialchars($id); ?>">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control campo-obligatorio" id="name" name="name" value="<?= isset($user) ? $user->getName() : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="surname">Surname</label>
                                <input type="text" class="form-control campo-obligatorio" id="surname" name="surname" value="<?= isset($user) ? $user->getSurname() : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control campo-obligatorio" id="email" name="email" value="<?= isset($user) ? $user->getEmail() : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="tel" class="form-control campo-obligatorio" id="phone" name="phone" value="<?= isset($user) ? $user->getPhone() : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control campo-obligatorio" id="password" name="password" value="<?= isset($user) ? "****" : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirm Password</label>
                                <input type="password" class="form-control campo-obligatorio" id="confirmPassword" name="confirmPassword" value="<?= isset($user) ? "****" : "" ?>" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 text-left">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" class="form-control campo-obligatorio" id="country" name="country" value="<?= isset($user) ? $user->getCountry() : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="province">Province</label>
                                <input type="text" class="form-control campo-obligatorio" id="province" name="province" value="<?= isset($user) ? $user->getProvince() : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control campo-obligatorio" id="city" name="city" value="<?= isset($user) ? $user->getCity() : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control campo-obligatorio" id="address" name="address" value="<?= isset($user) ? $user->getAddress() : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="cp">C.P</label>
                                <input type="number" class="form-control campo-obligatorio text-right-input" id="cp" name="cp" value="<?= isset($user) ? $user->getCp() : 0 ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="grupo">Group</label>
                                <select class="form-control" name="grupo" id="grupo">
                                    <?php foreach ($grupos as $grupo) : ?>
                                        <option value="<?= $grupo->getId() ?>" <?= isset($user) && $user->getGrupo_id() == $grupo->getId() ? 'selected' : '' ?>>
                                            <?= $grupo->getNombre() ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <hr style="border: 1px solid #ccc;">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary submitBtn" style="width:20em; margin:0;" disabled><?= $btnText ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
   
