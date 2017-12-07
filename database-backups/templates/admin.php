<div class="database-backups wrap">
    <div style="display:none;" data-element="message"></div>
    <div style="display: none" data-element="loader">
        <div class="wBall" id="wBall_1">
            <div class="wInnerBall"></div>
        </div>
        <div class="wBall" id="wBall_2">
            <div class="wInnerBall"></div>
        </div>
        <div class="wBall" id="wBall_3">
            <div class="wInnerBall"></div>
        </div>
        <div class="wBall" id="wBall_4">
            <div class="wInnerBall"></div>
        </div>
        <div class="wBall" id="wBall_5">
            <div class="wInnerBall"></div>
        </div>
    </div>

    <h1><?php _e('Database Backups', $data['key']); ?></h1>

    <div class="header-buttons">
        <button class="button button-controls"
                data-action="toggle"
                data-param-container="options"
                data-param-show="<?php echo empty($data['directory']) ? 'true' : 'false'; ?>">
            <?php _e('Settings', $data['key']); ?>
        </button>
        <button class="button button-primary" data-action="createBackup">
            <?php _e('Create backup now', $data['key']); ?>
        </button>
    </div>
    <form class="options-wrap" data-container="options" style="display: none;" data-element="optionsForm">
        <table class="form-table">
            <tr>
                <td>
                    <label for="directory">
                        <?php _e('Directory name', $data['key']); ?>
                    </label>
                </td>
                <td>
                    <?php echo $data['wp_upload_dir']['baseurl']; ?>/
                    <input type="text" id="directory" name="directory" class="input-form"
                           placeholder="<?php echo $data['directory']; ?>"
                           value="<?php echo $data['directory']; ?>" required>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="limit">
                        <?php _e('Use limits', $data['key']); ?>
                    </label>
                </td>
                <td>
                    <input type="number" id="limit" min="0" max="1000" name="limit" class="input-form"
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
                <td>
                    <label for="prefix">
                        <?php _e('Check prefix', $data['key']); ?>
                    </label>
                </td>
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
                <td>
                    <label for="clean">
                        <?php _e('Clean database', $data['key']); ?>
                    </label>
                </td>
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
                <td>
                    <label for="notify">
                        <?php _e('Notification to admin', $data['key']); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox" id="notify" name="notify"
                        <?php echo true === $data['notify'] ? 'checked' : ''; ?>>
                </td>
                <td>
                    <p class="description"><?php _e('Send Email to admin with result of backup operation',
                            $data['key']); ?></p>
                </td>
            </tr>
            <tr style="display: <?php echo true !== $data['gzip_status'] ? 'none' : 'table-row'; ?>">
                <td>
                    <label for="gzip">
                        <?php _e('Enable GZip compression', $data['key']); ?>
                    </label>

                </td>
                <td>
                    <input type="checkbox" name="gzip" id="gzip"
                        <?php echo true === $data['gzip'] ? 'checked' : '' ?>>
                </td>
                <td>
                    <p class="description">
                        <?php _e('Allow to pack your backup in GZ archive', $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tr style="display: <?php echo true !== $data['utf8_status'] ? 'none' : 'table-row'; ?>">
                <td>
                    <label for="utf8">
                        <?php _e('Enable converting to UTF-8', $data['key']); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox" name="utf8" id="utf8"
                        <?php echo true === $data['utf8'] ? 'checked' : ''; ?>>
                </td>
                <td>
                    <p class="description">
                        <?php _e('You can check this option if have problems with encoding when import database',
                            $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cron">
                        <?php _e('Enable auto backups', $data['key']); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox" name="cron" id="cron"
                           data-action="toggle"
                           data-param-container="cron"
                           data-param-show="<?php echo true === $data['cron'] ? 'true' : 'false'; ?>"
                        <?php echo true === $data['cron'] ? 'checked' : ''; ?>>
                </td>
                <td>
                </td>
            </tr>
            <tbody data-container="cron" style="display: none;">
            <tr>
                <td>
                    <label for="cron_frequency">
                        <?php _e('Auto backups frequency', $data['key']); ?>
                    </label>
                </td>
                <td>
                    <select name="cron_frequency" id="cron_frequency" class="input-form">
                        <?php foreach ($data['schedules'] as $cron => $value): ?>
                            <option value="<?php echo $cron; ?>" <?php echo ($data['cron_frequency'] === $cron) ? 'selected' : ''; ?>>
                                <?php echo $value['display'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                </td>
            </tr>
            </tbody>
            <tr>
                <td>
                    <label for="delete">
                        <?php _e('Enable auto delete', $data['key']); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox" id="delete" name="delete"
                           data-action="toggle"
                           data-param-container="delete"
                           data-param-show="<?php echo true === $data['delete'] ? 'true' : 'false'; ?>"
                        <?php echo true === $data['delete'] ? 'checked' : ''; ?>>
                </td>
                <td>
                    <p class="description">
                        <?php _e('Check this option if you want auto deleting the old backups', $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tbody data-container="delete" style="display: none">
            <tr>
                <td>
                    <label for="delete_days">
                        <?php _e('Delete after * days', $data['key']); ?>
                    </label>
                </td>
                <td>
                    <input type="number" id="delete_days" name="delete_days" class="input-form"
                           value="<?php echo $data['delete_days'] ?>" min="0" max="1000">
                </td>
                <td>
                    <p class="description">
                        <?php _e('How many days to store the backups. 0 - for disable this option',
                            $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="delete_copies">
                        <?php _e('Delete after * copies', $data['key']); ?>
                    </label>
                </td>
                <td>
                    <input type="number" id="delete_copies" name="delete_copies" class="input-form"
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
            </tbody>
            <tr>
                <td>
                    <label for="amazon_s3">
                        <?php _e('Use Amazon S3', $data['key']); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox" name="amazon_s3"
                           id="amazon_s3"
                           data-action="toggle"
                           data-param-container="amazon_s3"
                           data-param-show="<?php echo true === $data['amazon_s3'] ? 'true' : 'false'; ?>"
                        <?php echo true === $data['amazon_s3'] ? 'checked' : ''; ?>>
                </td>
                <td>
                    <p class="description">
                        <?php _e('Use Amazon s3 to manage your backups as well',
                            $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tbody data-container="amazon_s3" style="display: none;">
            <tr>
                <td>
                    <label for="amazon_s3_region">
                        <?php _e('Amazon S3 Region', $data['key']); ?>
                    </label>
                </td>
                <td>
                    <input type="text" id="amazon_s3_region" name="amazon_s3_region" class="input-form"
                           value="<?php echo $data['amazon_s3_region']; ?>">
                </td>
                <td>
                    <p class="description">
                        <?php _e('<a href="http://docs.aws.amazon.com/general/latest/gr/rande.html#apigateway_region" target=_blank>Region</a> of your bucket.',
                            $data['key']); ?>
                        <?php _e('Put there code of region of your bucket, for example Canada (Central) it\'s <strong>ca-central-1</strong>',
                            $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="amazon_s3_bucket">
                        <?php _e('Amazon S3 Bucket', $data['key']); ?>
                    </label>
                </td>
                <td>
                    <input type="text" id="amazon_s3_bucket" name="amazon_s3_bucket" class="input-form"
                           value="<?php echo $data['amazon_s3_bucket']; ?>">
                </td>
                <td>
                    <p class="description">
                        <?php _e('Name of your bucket', $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="amazon_s3_key">
                        <?php _e('Amazon S3 Key', $data['key']); ?>
                    </label>
                </td>
                <td>
                    <input type="text" id="amazon_s3_key" name="amazon_s3_key" class="input-form"
                           value="<?php echo $data['amazon_s3_key']; ?>">
                </td>
                <td>
                    <p class="description">
                        <?php _e('Your can create your access keys in <a href="https://console.aws.amazon.com/iam/home?#/security_credential" target="_blank">Amazon S3 Console</a>.',
                            $data['key']); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="amazon_s3_secret">
                        <?php _e('Amazon S3 Secret Key', $data['key']); ?>
                    </label>
                </td>
                <td>
                    <input type="password" style="display: none">
                    <input type="password" id="amazon_s3_secret" name="amazon_s3_secret" class="input-form"
                           value="<?php echo $data['amazon_s3_secret']; ?>">
                </td>
                <td>
                    <button type="button" data-action="saveOptions" data-param="checkAmazonS3"
                            class="button button-primary">
                        <?php _e('Save changes and check', $data['key']); ?>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

        <button type="button" class="button button-primary" data-action="saveOptions">
            <?php _e('Save settings'); ?>
        </button>
    </form>

    <div class="wrap">
        <h2><?php _e('Previously created backups', $data['key']); ?></h2>

        <table class="wp-list-table widefat striped" data-element="backupsTable">
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
            <tr class="no-backups-yet"
                style="display:<?php echo (count($data['backups']) === 0) ? 'table-row' : 'none'; ?>" data-element="noBackups">
                <td colspan="5">
                    <?php _e('No backups yet. Create a <a href=# data-action="createBackup">new one</a> right now.', $data['key']); ?>
                </td>
            </tr>
            <?php foreach ($data['backups'] as $backup): ?>
                <tr>
                    <td>
                        <?php echo $data['i']++; ?>
                    </td>
                    <td>
                        <span class="icon dashicons <?php echo $backup['icon']; ?>"></span>
                        <?php echo $backup['name']; ?>
                    </td>
                    <td>
                        <?php echo $backup['size_mb']; ?>
                    </td>
                    <td>
                        <?php echo $backup['date_i18n']; ?>
                    </td>
                    <td>
                        <a href="<?php echo $backup['url']; ?>" class="button button-cancel">
                            <span class="icon dashicons dashicons-download"></span> <?php _e('Download',
                                $data['key']); ?></a>
                        <button data-action="deleteBackup" data-param="<?php echo $backup['name']; ?>"
                                class="button button-cancel">
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
            <button class="button button-primary" data-action="createBackup">
                <?php _e('Create backup now', $data['key']); ?>
            </button>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function () {
        new DatabaseBackups({
            key: '<?php echo $data['key'];?>',
            admin_url: '<?php echo $data['admin_url']; ?>',
            nonce: '<?php echo $data['nonce'];?>'
        });
    });
</script>