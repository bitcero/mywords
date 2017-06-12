<h1><{$lang.reportPost}></h1>

<form name="formReport" method="post" action="<{$reportLink}>">

    <{if $reportAnonym}>
        <div class="form-group">
            <label for="report-name"><{$lang.name}></label>
            <input type="text" autocomplete="off" class="form-control" name="name" id="report-name" required>
        </div>
        <div class="form-group">
            <label for="report-email"><{$lang.email}></label>
            <input type="email" autocomplete="off" class="form-control" name="email" id="report-email" required>
        </div>
    <{/if}>

    <div class="form-group">
        <label for="report-title"><{$lang.title}></label>
        <input type="title" autocomplete="off" class="form-control" name="title" id="report-title" maxlength="60" required>
        <small class="help-block"><{$lang.reasonTitle}></small>
    </div>

    <div class="form-group">
        <label for="report-content"><{$lang.content}></label>
        <textarea class="form-control" name="content" id="report-content" rows="5" required></textarea>
        <small class="help-block"><{$lang.whyContent}></small>
    </div>

    <{if $captcha}>
        <div class="form-group">
            <{$captcha}>
        </div>
    <{/if}>

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg"><{$lang.send}></button>
        <button type="button" class="btn btn-default btn-lg" onclick="history.go(-1);"><{$lang.cancel}></button>
    </div>

    <input type="hidden" name="report" value="<{$post.id}>">
    <input type="hidden" name="action" value="submit">

</form>