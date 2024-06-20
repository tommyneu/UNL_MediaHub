<div class="dcf-bleed dcf-pt-6 dcf-mb-6">
    <div class="dcf-wrapper">
        <h2 class="dcf-txt-h2">Transcription Manager</h2>
        <div class="dcf-grid dcf-col-gap-vw dcf-row-gap-4">
            <div class="dcf-col-100% dcf-col-25%-start@md">
                <h3 class="dcf-txt-h6">Transcriber Worker Status</h3>
                <table class="dcf-table dcf-table-bordered dcf-table-responsive">
                    <thead>
                        <th scope="col">Name</th>
                        <th scope="col">Status</th>
                    </thead>
                    <?php if (
                        $context->getAPIData()['status'] === 'OK' &&
                        isset($context->getAPIData()['data']) &&
                        isset($context->getAPIData()['data']->worker_status) &&
                        !empty($context->getAPIData()['data']->worker_status)
                    ): ?>
                        <?php foreach($context->getAPIData()['data']->worker_status as $worker_name => $worker_status): ?>
                            <tr>
                                <td data-label="worker name"><?php echo $worker_name; ?></td>
                                <td data-label="worker status"><?php echo ucwords($worker_status); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Can't get worker's statuses</p>
                    <?php endif; ?>
                </table>
            </div>
            <div class="dcf-col-100% dcf-col-75%-end@md">
                <h3 class="dcf-txt-h6" id="table-description">Recent Transcription Jobs <span class="dcf-subhead">Last 50 jobs sorted by Date</span></h3>
                <?php if ($context->hasJobs()) { ?>
                    <table class="dcf-table dcf-table-bordered dcf-table-responsive" aria-describedby="table-description">
                        <thead>
                            <th scope="col">ID</th>
                            <th scope="col">Job ID</th>
                            <th scope="col">User ID</th>
                            <th scope="col">Status</th>
                            <th scope="col">Auto Activate</th>
                            <th scope="col">Date Created</th>
                            <th scope="col">Date Updated</th>
                        </thead>
                        <tbody>
                        <?php foreach($context->getJobs() as $job): ?>
                            <tr>
                                <td class="dcf-txt-right" data-label="ID"><?php echo $job->id; ?></td>
                                <td class="dcf-txt-right" data-label="Job ID"><?php echo $job->job_id; ?></td>
                                <td data-label="user id"><?php echo $job->uid; ?></td>
                                <td data-label="status"><?php echo ucwords(strtolower($job->status)); ?></td>
                                <td data-label="auto activate"><?php echo $job->auto_activate === '1' ? 'Yes': 'No'; ?></td>
                                <td data-label="date created"><?php echo $job->datecreated; ?></td>
                                <td data-label="date updated"><?php echo $job->dateupdated; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>There are not any jobs transcoding.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
