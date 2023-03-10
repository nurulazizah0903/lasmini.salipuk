<?php

namespace PHPMaker2022\pubinamarga;

// Set up and run Grid object
$Grid = Container("BroadcastTujuanGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fbroadcast_tujuangrid;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fbroadcast_tujuangrid = new ew.Form("fbroadcast_tujuangrid", "grid");
    fbroadcast_tujuangrid.formKeyCountName = "<?= $Grid->FormKeyCountName ?>";

    // Add fields
    var currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { broadcast_tujuan: currentTable } });
    var fields = currentTable.fields;
    fbroadcast_tujuangrid.addFields([
        ["pelapor_id", [fields.pelapor_id.visible && fields.pelapor_id.required ? ew.Validators.required(fields.pelapor_id.caption) : null], fields.pelapor_id.isInvalid]
    ]);

    // Check empty row
    fbroadcast_tujuangrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm(),
            fields = [["pelapor_id",false]];
        if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
            return false;
        return true;
    }

    // Form_CustomValidate
    fbroadcast_tujuangrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fbroadcast_tujuangrid.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fbroadcast_tujuangrid.lists.pelapor_id = <?= $Grid->pelapor_id->toClientList($Grid) ?>;
    loadjs.done("fbroadcast_tujuangrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> broadcast_tujuan">
<div id="fbroadcast_tujuangrid" class="ew-form ew-list-form">
<div id="gmp_broadcast_tujuan" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_broadcast_tujuangrid" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Grid->RowType = ROWTYPE_HEADER;

// Render list options
$Grid->renderListOptions();

// Render list options (header, left)
$Grid->ListOptions->render("header", "left");
?>
<?php if ($Grid->pelapor_id->Visible) { // pelapor_id ?>
        <th data-name="pelapor_id" class="<?= $Grid->pelapor_id->headerCellClass() ?>"><div id="elh_broadcast_tujuan_pelapor_id" class="broadcast_tujuan_pelapor_id"><?= $Grid->renderFieldHeader($Grid->pelapor_id) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Grid->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
$Grid->StartRecord = 1;
$Grid->StopRecord = $Grid->TotalRecords; // Show all records

// Restore number of post back records
if ($CurrentForm && ($Grid->isConfirm() || $Grid->EventCancelled)) {
    $CurrentForm->Index = -1;
    if ($CurrentForm->hasValue($Grid->FormKeyCountName) && ($Grid->isGridAdd() || $Grid->isGridEdit() || $Grid->isConfirm())) {
        $Grid->KeyCount = $CurrentForm->getValue($Grid->FormKeyCountName);
        $Grid->StopRecord = $Grid->StartRecord + $Grid->KeyCount - 1;
    }
}
$Grid->RecordCount = $Grid->StartRecord - 1;
if ($Grid->Recordset && !$Grid->Recordset->EOF) {
    // Nothing to do
} elseif ($Grid->isGridAdd() && !$Grid->AllowAddDeleteRow && $Grid->StopRecord == 0) {
    $Grid->StopRecord = $Grid->GridAddRowCount;
}

// Initialize aggregate
$Grid->RowType = ROWTYPE_AGGREGATEINIT;
$Grid->resetAttributes();
$Grid->renderRow();
while ($Grid->RecordCount < $Grid->StopRecord) {
    $Grid->RecordCount++;
    if ($Grid->RecordCount >= $Grid->StartRecord) {
        $Grid->RowCount++;
        if ($Grid->isAdd() || $Grid->isGridAdd() || $Grid->isGridEdit() || $Grid->isConfirm()) {
            $Grid->RowIndex++;
            $CurrentForm->Index = $Grid->RowIndex;
            if ($CurrentForm->hasValue($Grid->FormActionName) && ($Grid->isConfirm() || $Grid->EventCancelled)) {
                $Grid->RowAction = strval($CurrentForm->getValue($Grid->FormActionName));
            } elseif ($Grid->isGridAdd()) {
                $Grid->RowAction = "insert";
            } else {
                $Grid->RowAction = "";
            }
        }

        // Set up key count
        $Grid->KeyCount = $Grid->RowIndex;

        // Init row class and style
        $Grid->resetAttributes();
        $Grid->CssClass = "";
        if ($Grid->isGridAdd()) {
            if ($Grid->CurrentMode == "copy") {
                $Grid->loadRowValues($Grid->Recordset); // Load row values
                $Grid->OldKey = $Grid->getKey(true); // Get from CurrentValue
            } else {
                $Grid->loadRowValues(); // Load default values
                $Grid->OldKey = "";
            }
        } else {
            $Grid->loadRowValues($Grid->Recordset); // Load row values
            $Grid->OldKey = $Grid->getKey(true); // Get from CurrentValue
        }
        $Grid->setKey($Grid->OldKey);
        $Grid->RowType = ROWTYPE_VIEW; // Render view
        if ($Grid->isGridAdd()) { // Grid add
            $Grid->RowType = ROWTYPE_ADD; // Render add
        }
        if ($Grid->isGridAdd() && $Grid->EventCancelled && !$CurrentForm->hasValue("k_blankrow")) { // Insert failed
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }
        if ($Grid->isGridEdit()) { // Grid edit
            if ($Grid->EventCancelled) {
                $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
            }
            if ($Grid->RowAction == "insert") {
                $Grid->RowType = ROWTYPE_ADD; // Render add
            } else {
                $Grid->RowType = ROWTYPE_EDIT; // Render edit
            }
        }
        if ($Grid->isGridEdit() && ($Grid->RowType == ROWTYPE_EDIT || $Grid->RowType == ROWTYPE_ADD) && $Grid->EventCancelled) { // Update failed
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }
        if ($Grid->RowType == ROWTYPE_EDIT) { // Edit row
            $Grid->EditRowCount++;
        }
        if ($Grid->isConfirm()) { // Confirm row
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }

        // Set up row attributes
        $Grid->RowAttrs->merge([
            "data-rowindex" => $Grid->RowCount,
            "id" => "r" . $Grid->RowCount . "_broadcast_tujuan",
            "data-rowtype" => $Grid->RowType,
            "class" => ($Grid->RowCount % 2 != 1) ? "ew-table-alt-row" : "",
        ]);
        if ($Grid->isAdd() && $Grid->RowType == ROWTYPE_ADD || $Grid->isEdit() && $Grid->RowType == ROWTYPE_EDIT) { // Inline-Add/Edit row
            $Grid->RowAttrs->appendClass("table-active");
        }

        // Render row
        $Grid->renderRow();

        // Render list options
        $Grid->renderListOptions();

        // Skip delete row / empty row for confirm page
        if (
            $Page->RowAction != "delete" &&
            $Page->RowAction != "insertdelete" &&
            !($Page->RowAction == "insert" && $Page->isConfirm() && $Page->emptyRow())
        ) {
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowCount);
?>
    <?php if ($Grid->pelapor_id->Visible) { // pelapor_id ?>
        <td data-name="pelapor_id"<?= $Grid->pelapor_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_broadcast_tujuan_pelapor_id" class="el_broadcast_tujuan_pelapor_id">
    <select
        id="x<?= $Grid->RowIndex ?>_pelapor_id"
        name="x<?= $Grid->RowIndex ?>_pelapor_id"
        class="form-select ew-select<?= $Grid->pelapor_id->isInvalidClass() ?>"
        data-select2-id="fbroadcast_tujuangrid_x<?= $Grid->RowIndex ?>_pelapor_id"
        data-table="broadcast_tujuan"
        data-field="x_pelapor_id"
        data-value-separator="<?= $Grid->pelapor_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->pelapor_id->getPlaceHolder()) ?>"
        <?= $Grid->pelapor_id->editAttributes() ?>>
        <?= $Grid->pelapor_id->selectOptionListHtml("x{$Grid->RowIndex}_pelapor_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->pelapor_id->getErrorMessage() ?></div>
<?= $Grid->pelapor_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_pelapor_id") ?>
<script>
loadjs.ready("fbroadcast_tujuangrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_pelapor_id", selectId: "fbroadcast_tujuangrid_x<?= $Grid->RowIndex ?>_pelapor_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fbroadcast_tujuangrid.lists.pelapor_id.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_pelapor_id", form: "fbroadcast_tujuangrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_pelapor_id", form: "fbroadcast_tujuangrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.broadcast_tujuan.fields.pelapor_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="broadcast_tujuan" data-field="x_pelapor_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_pelapor_id" id="o<?= $Grid->RowIndex ?>_pelapor_id" value="<?= HtmlEncode($Grid->pelapor_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_broadcast_tujuan_pelapor_id" class="el_broadcast_tujuan_pelapor_id">
    <select
        id="x<?= $Grid->RowIndex ?>_pelapor_id"
        name="x<?= $Grid->RowIndex ?>_pelapor_id"
        class="form-select ew-select<?= $Grid->pelapor_id->isInvalidClass() ?>"
        data-select2-id="fbroadcast_tujuangrid_x<?= $Grid->RowIndex ?>_pelapor_id"
        data-table="broadcast_tujuan"
        data-field="x_pelapor_id"
        data-value-separator="<?= $Grid->pelapor_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->pelapor_id->getPlaceHolder()) ?>"
        <?= $Grid->pelapor_id->editAttributes() ?>>
        <?= $Grid->pelapor_id->selectOptionListHtml("x{$Grid->RowIndex}_pelapor_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->pelapor_id->getErrorMessage() ?></div>
<?= $Grid->pelapor_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_pelapor_id") ?>
<script>
loadjs.ready("fbroadcast_tujuangrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_pelapor_id", selectId: "fbroadcast_tujuangrid_x<?= $Grid->RowIndex ?>_pelapor_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fbroadcast_tujuangrid.lists.pelapor_id.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_pelapor_id", form: "fbroadcast_tujuangrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_pelapor_id", form: "fbroadcast_tujuangrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.broadcast_tujuan.fields.pelapor_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_broadcast_tujuan_pelapor_id" class="el_broadcast_tujuan_pelapor_id">
<span<?= $Grid->pelapor_id->viewAttributes() ?>>
<?= $Grid->pelapor_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="broadcast_tujuan" data-field="x_pelapor_id" data-hidden="1" name="fbroadcast_tujuangrid$x<?= $Grid->RowIndex ?>_pelapor_id" id="fbroadcast_tujuangrid$x<?= $Grid->RowIndex ?>_pelapor_id" value="<?= HtmlEncode($Grid->pelapor_id->FormValue) ?>">
<input type="hidden" data-table="broadcast_tujuan" data-field="x_pelapor_id" data-hidden="1" name="fbroadcast_tujuangrid$o<?= $Grid->RowIndex ?>_pelapor_id" id="fbroadcast_tujuangrid$o<?= $Grid->RowIndex ?>_pelapor_id" value="<?= HtmlEncode($Grid->pelapor_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowCount);
?>
    </tr>
<?php if ($Grid->RowType == ROWTYPE_ADD || $Grid->RowType == ROWTYPE_EDIT) { ?>
<script>
loadjs.ready(["fbroadcast_tujuangrid","load"], () => fbroadcast_tujuangrid.updateLists(<?= $Grid->RowIndex ?>));
</script>
<?php } ?>
<?php
    }
    } // End delete row checking
    if (!$Grid->isGridAdd() || $Grid->CurrentMode == "copy")
        if (!$Grid->Recordset->EOF) {
            $Grid->Recordset->moveNext();
        }
}
?>
<?php
if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy" || $Grid->CurrentMode == "edit") {
    $Grid->RowIndex = '$rowindex$';
    $Grid->loadRowValues();

    // Set row properties
    $Grid->resetAttributes();
    $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_broadcast_tujuan", "data-rowtype" => ROWTYPE_ADD]);
    $Grid->RowAttrs->appendClass("ew-template");

    // Reset previous form error if any
    $Grid->resetFormError();

    // Render row
    $Grid->RowType = ROWTYPE_ADD;
    $Grid->renderRow();

    // Render list options
    $Grid->renderListOptions();
    $Grid->StartRowCount = 0;
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowIndex);
?>
    <?php if ($Grid->pelapor_id->Visible) { // pelapor_id ?>
        <td data-name="pelapor_id">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_broadcast_tujuan_pelapor_id" class="el_broadcast_tujuan_pelapor_id">
    <select
        id="x<?= $Grid->RowIndex ?>_pelapor_id"
        name="x<?= $Grid->RowIndex ?>_pelapor_id"
        class="form-select ew-select<?= $Grid->pelapor_id->isInvalidClass() ?>"
        data-select2-id="fbroadcast_tujuangrid_x<?= $Grid->RowIndex ?>_pelapor_id"
        data-table="broadcast_tujuan"
        data-field="x_pelapor_id"
        data-value-separator="<?= $Grid->pelapor_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->pelapor_id->getPlaceHolder()) ?>"
        <?= $Grid->pelapor_id->editAttributes() ?>>
        <?= $Grid->pelapor_id->selectOptionListHtml("x{$Grid->RowIndex}_pelapor_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->pelapor_id->getErrorMessage() ?></div>
<?= $Grid->pelapor_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_pelapor_id") ?>
<script>
loadjs.ready("fbroadcast_tujuangrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_pelapor_id", selectId: "fbroadcast_tujuangrid_x<?= $Grid->RowIndex ?>_pelapor_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fbroadcast_tujuangrid.lists.pelapor_id.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_pelapor_id", form: "fbroadcast_tujuangrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_pelapor_id", form: "fbroadcast_tujuangrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.broadcast_tujuan.fields.pelapor_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_broadcast_tujuan_pelapor_id" class="el_broadcast_tujuan_pelapor_id">
<span<?= $Grid->pelapor_id->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->pelapor_id->getDisplayValue($Grid->pelapor_id->ViewValue) ?></span></span>
</span>
<input type="hidden" data-table="broadcast_tujuan" data-field="x_pelapor_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_pelapor_id" id="x<?= $Grid->RowIndex ?>_pelapor_id" value="<?= HtmlEncode($Grid->pelapor_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="broadcast_tujuan" data-field="x_pelapor_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_pelapor_id" id="o<?= $Grid->RowIndex ?>_pelapor_id" value="<?= HtmlEncode($Grid->pelapor_id->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fbroadcast_tujuangrid","load"], () => fbroadcast_tujuangrid.updateLists(<?= $Grid->RowIndex ?>, true));
</script>
    </tr>
<?php
}
?>
</tbody>
</table><!-- /.ew-table -->
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "edit") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "") { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fbroadcast_tujuangrid">
</div><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Grid->Recordset) {
    $Grid->Recordset->close();
}
?>
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php $Grid->OtherOptions->render("body", "bottom") ?>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php if (!$Grid->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("broadcast_tujuan");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
