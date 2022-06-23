<?php
require_once('includes/init.php');
$judul_page = 'Assesment Employee - TOPSIS & SAW';
require_once('template-parts/header.php');
?>



<?php $user_role ?>

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
        <div class="statbox widget box">
            <div class="widget-header ">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>Online User</h4>
                    </div>
                </div>
            </div>

            <div class="widget-content-area ">

                <div class="table-responsive new-products">
                    <?php
                    $query = $pdo->prepare('SELECT id_user, username, nama, role,email,password FROM user');
                    $query->execute();
                    // menampilkan berupa nama field
                    $query->setFetchMode(PDO::FETCH_ASSOC);

                    if ($query->rowCount() > 0) :
                    ?>
                        <?php $user_role = get_role(); ?>
                        <?php if ($user_role == 'admin') : ?>
                            <table class="table">
                                <thead>
                                    <tr>

                                        <th>ID</th>
                                        <th>Username</th>

                                        <th>Role</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th>Edit</th>
                                        <th>Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($hasil = $query->fetch()) : ?>
                                        <tr>
                                            <td><?php echo $hasil['id_user']; ?></td>
                                            <td><?php echo $hasil['username']; ?></td>
                                            <td><?php echo $hasil['nama']; ?></td>
                                            <td>
                                                <?php
                                                if ($hasil['role'] == 1) {
                                                    echo 'Administrator';
                                                } elseif ($hasil['role'] == 2) {
                                                    echo 'Petugas';
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $hasil['email']; ?></td>
                                            <td><?php echo $hasil['password']; ?></td>
                                            <td><a href="edit-user.php?id=<?php echo $hasil['id_user']; ?>"><span class="fa fa-pencil"></span> Edit</a></td>
                                            <td><a href="hapus-user.php?id=<?php echo $hasil['id_user']; ?>" class="red yaqin-hapus"><span class="fa fa-times"></span> Hapus</a></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <p>Maaf, belum ada data yang ditampilkan.</p>
                                <?php endif; ?>



                                </tbody>
                            </table>
                        <?php endif; ?>
                </div>
                <div class="pagination-section">
                    <ul class="pagination pagination-style-1 pagination-rounded justify-content-end mt-3 mb-3">
                        <li><a href="javascript:void(0);">«</a></li>
                        <li><a href="javascript:void(0);">1</a></li>
                        <li><a href="javascript:void(0);">2</a></li>
                        <li><a href="javascript:void(0);">3</a></li>
                        <li><a href="javascript:void(0);">4</a></li>
                        <li><a href="javascript:void(0);">5</a></li>
                        <li><a href="javascript:void(0);">»</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>






<?php
require_once('template-parts/footer.php');
?>