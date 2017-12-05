<div class="database-backups wrap">
    <h1><?php _e('Database Backups', $data['key']); ?></h1>
    <h2>
        <button class="button button-controls options-toggle">
            <?php _e('Settings', $data['key']); ?>
        </button>
    </h2>
    <form class="options-wrap" id="options">
        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="directory">
                        <?php _e('Directory name', $data['key']); ?>
                    </label>
                </th>
                <td>
                    <?php echo $data['wp_upload_dir']['baseurl']; ?>/
                    <input type="text" id="directory" name="directory"
                           placeholder="<?php echo $data['directory']; ?>"
                           value="<?php echo $data['directory']; ?>" required>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="limit">
                        <?php _e('Use limits', $data['key']); ?>
                    </label>
                </th>
                <td>
                    <input type="number" id="limit" min="0" max="1000" name="limit"
                           placeholder="<?php echo $data['limit']; ?>"
                           value="<?php echo $data['limit']; ?>">
                </td>
                <td>
                    <p class="description">
                        <?php _e('Set round value if you see PHP memory error. 0 - don\'t use limits',
                            $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="prefix">
                        <?php _e('Check prefix', $data['key']); ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="prefix" name="prefix"
                        <?php echo true === $data['prefix'] ? 'checked' : ''; ?>>
                </td>
                <td>
                    <p class="description">
                        <?php _e('Backup only WP tables with prefix', $data['key']); ?>
                        "<?php echo $data['prefix_default'] ?>"
                    </p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="clean">
                        <?php _e('Clean database', $data['key']); ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="clean" name="clean"
                        <?php echo true === $data['clean'] ? 'checked' : ''; ?>>
                </td>
                <td>
                    <p class="description">
                        <?php _e('Not save in backup: posts/page revisions, posts/page in trash, spam comments, comments in trash',
                            $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="notify">
                        <?php _e('Notification to admin', $data['key']); ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="notify" name="notify"
                        <?php echo true === $data['notify'] ? 'checked' : ''; ?>>
                </td>
                <td>
                    <p class="description"><?php _e('Send Email to admin with result of backup operation',
                            $data['key']); ?></p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="gzip">
                        <?php _e('Enable GZip compression', $data['key']); ?>
                    </label>

                </th>
                <td>
                    <input type="checkbox" name="gzip" id="gzip"
                        <?php echo true === $data['gzip'] ? 'checked' : '' ?>
                        <?php echo true !== $data['gzip_status'] ? 'disabled' : ''; ?>>
                </td>
                <td>
                    <p class="description">
                        <?php _e('Allow to pack your backup in GZ archive', $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="utf8">
                        <?php _e('Enable converting to UTF-8', $data['key']); ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" name="utf8" id="utf8"
                        <?php echo true === $data['utf8'] ? 'checked' : ''; ?>
                        <?php echo true !== $data['utf8_status'] ? 'disabled' : ''; ?>>
                </td>
                <td>
                    <p class="description">
                        <?php _e('You can check this option if have problems with encoding when import database',
                            $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="cron_auto">
                        <?php _e('Enable auto backups', $data['key']); ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" name="cron_auto" id="utf8"
                        <?php echo true === $data['cron_auto'] ? 'checked' : ''; ?>>
                </td>
                <td>
                </td>
            </tr>
            <tr data-container="cron_auto" style="display: none;">
                <th>
                    <label for="cron">
                    <?php _e('Enable auto backups', $data['key']); ?>
                    </label>
                </th>
                <td>
                    <select name="cron" id="cron">
                        <option value="" <?php echo null === $data['cron_option'] ? 'selected' : ''; ?>>
                            <?php _e('Disabled', $data['key']); ?>
                        </option>
                        <?php foreach ($data['schedules'] as $cron => $value): ?>
                            <option value="<?php echo $cron; ?>" <?php echo ($data['cron_option'] === $cron) ? 'selected' : ''; ?>>
                                <?php echo $value['display'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="delete">
                    <?php _e('Enable auto delete', $data['key']); ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="delete" name="delete"
                        <?php echo true === $data['delete'] ? 'checked' : ''; ?>>
                </td>
                <td>
                    <p class="description">
                        <?php _e('Check this option if you want auto deleting the old backups', $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tr data-container="delete">
                <th>
                    <label for="delete_days">
                    <?php _e('Delete after * days', $data['key']); ?>
                    </label>
                </th>
                <td>
                    <input type="number" id="delete_days" name="delete_days"
                           value="<?php echo $data['delete_days'] ?>" min="0" max="1000">
                </td>
                <td>
                    <p class="description">
                        <?php _e('How many days to store the backups. 0 - for disable this option',
                            $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tr data-container="delete">
                <th>
                    <label for="delete_copies">
                    <?php _e('Delete after * copies', $data['key']); ?>
                    </label>
                </th>
                <td>
                    <input type="number" id="delete_copies" name="delete_copies"
                           value="<?php echo $data['delete_copies'] ?>"
                           min="0" max="1000">
                </td>
                <td>
                    <p class="description">
                        <?php _e('How many copies of backups to store. 0 - for disable this option',
                            $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="amazon_s3">
                    <?php _e('Use Amazon S3 to store copies', $data['key']); ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" name="amazon_s3"
                           id="amazon_s3"
                           <?php echo true === $data['amazon_s3'] ? 'checked' : ''; ?>>
                </td>
                <td>
                    <p class="description">
                        <?php _e('Use Amazon s3 to manage copies of your backups as well',
                            $data['key']); ?>
                    </p>
                </td>
            <tr data-container="amazon_s3" style="display: none;">
                <td>
                    <label for="amazon_s3_region">
                        <?php _e('Amazon S3 Region', $data['key']);?>
                    </label>
                </td>
                <td>
                    <input type="text" id="amazon_s3_region" name="amazon_s3_region"
                    value="<?php echo $data['amazon_s3_region'];?>">
                </td>
                <td>
                    <?php _e('Region of your bucket', $data['key']);?>
                </td>
            </tr>
            <tr data-container="amazon_s3" style="display: none;">
                <td>
                    <label for="amazon_s3_bucket">
                        <?php _e('Amazon S3 Bucket', $data['key']);?>
                    </label>
                </td>
                <td>
                    <input type="text" id="amazon_s3_bucket" name="amazon_s3_bucket"
                           value="<?php echo $data['amazon_s3_bucket'];?>">
                </td>
                <td>
                    <?php _e('Region of your bucket', $data['key']);?>
                </td>
            </tr>
            <tr data-container="amazon_s3" style="display: none;">
                <td>
                    <label for="amazon_s3_key">
                        <?php _e('Amazon S3 Key', $data['key']);?>
                    </label>
                </td>
                <td>
                    <input type="text" id="amazon_s3_key" name="amazon_s3_key"
                           value="<?php echo $data['amazon_s3_key'];?>">
                </td>
                <td>
                    <?php _e('Your can create your access keys in <a href="https://console.aws.amazon.com/iam/home?#/security_credential" target="_blank">Amazon S3 Console</a>.', $data['key']);?>
                </td>
            </tr>
            <tr data-container="amazon_s3" style="display: none;">
                <td>
                    <label for="amazon_s3_secret">
                        <?php _e('Amazon S3 Secret Key', $data['key']);?>
                    </label>
                </td>
                <td>
                    <input type="text" id="amazon_s3_secret" name="amazon_s3_secret"
                           value="<?php echo $data['amazon_s3_secret'];?>">
                </td>
                <td>
                </td>
            </tr>
            </tbody>
        </table>

        <button type="button" class="button button-primary" data-action="save-settings">
            <?php _e('Save settings'); ?>
        </button>
    </form>

    <div class="wrap">
        <h2><?php _e('Previously created backups', $data['key']); ?></h2>

        <table class='wp-list-table widefat striped'>
            <thead>
            <tr>
                <th><?php _e('ID', $data['key']); ?></th>
                <th><?php _e('File Name', $data['key']); ?></th>
                <th><?php _e('Size, MB', $data['key']); ?></th>
                <th><?php _e('Date', $data['key']); ?></th>
                <th><?php _e('Actions', $data['key']); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if (count($data['backups']) === 0): ?>
                <tr class="no-backups-yet">
                    <td colspan="5">
                        <?php _e('No backups yet. Create a new one right now.', $data['key']); ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php foreach ($data['backups'] as $backup): ?>
                <tr>
                    <td>
                        <?php echo $data['i']++; ?>
                    </td>
                    <td>
                        <span class="icon dashicons <?php echo $backup['format'] === 'sql' ? 'dashicons-media-spreadsheet' : 'dashicons-portfolio'; ?>"></span>
                        <?php echo $backup['name']; ?>
                    </td>
                    <td>
                        <?php echo $backup['size_mb']; ?>
                    </td>
                    <td>
                        <?php echo $backup['date_i18n']; ?>
                    </td>
                    <td>
                        <a href="<?php echo $backup['url']; ?>" class="button button-link download">
                            <span class="icon dashicons dashicons-download"></span> <?php _e('Download',
                                $data['key']); ?></a>
                        <button data-id="<?php echo $backup['name']; ?>" class="button button-cancel delete">
                            <span class="icon dashicons dashicons-trash"></span> <?php _e('Delete', $data['key']); ?>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th><?php echo $data['occupied_space']; ?> MB / <?php echo $data['total_free_space']; ?> MB</th>
                <th></th>
                <th></th>
            </tr>
            </tfoot>
        </table>
        <div class="footer-buttons">
            <button class="button button-primary">
                <?php _e('Create backup now', $data['key']); ?>
            </button>
        </div>
    </div>
</div>
<script>
    var DatabaseBackups = {
        key: '<?php echo $data['key'];?>',
        admin_url: '<?php echo $data['admin_url']; ?>',
        nonce: '<?php echo $data['nonce'];?>'
    };

    jQuery('.settings-toggle').click(function () {
        var s = jQuery('.settings-wrap');

        if (s.css('display') === 'block')
            s.fadeOut();
        else
            s.fadeIn(200);
    });

    jQuery('.auto-delete-checkbox').click(function () {
        var c = jQuery(this);
        var a = jQuery('.auto-delete');

        if (c.attr('checked')) {
            a.fadeIn(200);
        } else {
            a.fadeOut(200);
        }
    });
</script>