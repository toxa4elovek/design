<p>
    <label class="required">Формат файла <a href="#" class="second tooltip" title="Необходимо указать формат, который на выходе предоставит вам дизайнер. Мы советуем обратиться в типографию или веб-мастеру и уточнить технические требования.">(?)</a></label>
</p>

<?php
$defaults = [
    '1' => ['EPS', 'JPG', 'PNG'],
    '2' => ['PSD', 'GIF', 'JPG', 'PNG'],
    '3' => ['PSD', 'JPG'],
    '4' => ['Indd', 'TIFF', 'JPG'],
    '5' => ['EPS', 'JPG'],
    '6' => ['PSD', 'EPS', 'JPG'],
    '7' => ['PDF', 'DOC', 'TXT'],
    '8' => [],
    '9' => ['TIFF', 'JPG'],
    '10' => ['EPS', 'PSD', 'JPG'],
    '11' => ['EPS', 'JPG'],
    '12' => ['EPS', 'TIFF', 'JPG'],
    '13' => ['EPS', 'JPG'],
    '20' => ['EPS', 'PSD', 'JPG'],
];

function extensionsCheckboxChecked($extension, $listOfExtensionsOfCategories, $category, $pitch = null)
{
    if (isset($pitch)):
        $haystack = unserialize($pitch->fileFormats); else:
        $haystack = $listOfExtensionsOfCategories[$category->id];
    endif;
    $checkResult = in_array($extension, $haystack);
    if ($checkResult):
        return 'checked';
    endif;
    return '';
}

if ($category->id != 7):?>
    <ul class="extensions">
        <li class="wide graysupplement"><label><input type="checkbox" name="" <?php echo extensionsCheckboxChecked('EPS', $defaults, $category, $pitch);?> data-value="EPS">.EPS</label></li>
        <li class="wide graysupplement"><label><input type="checkbox" name="" <?php echo extensionsCheckboxChecked('AI', $defaults, $category, $pitch);?> data-value="AI">.AI (Illustrator)</label></li>
        <li class="graysupplement"><label><input type="checkbox" name="" data-value="JPG" <?php echo extensionsCheckboxChecked('JPG', $defaults, $category, $pitch);?> >.JPG</label></li>
        <li class="graysupplement"><label><input type="checkbox" name="" data-value="PNG" <?php echo extensionsCheckboxChecked('PNG', $defaults, $category, $pitch);?> >.PNG</label></li>
        <li class="graysupplement"><label><input type="checkbox" name="" data-value="PDF" <?php echo extensionsCheckboxChecked('PDF', $defaults, $category, $pitch);?> >.PDF</label></li>
        <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="PSD" <?php echo extensionsCheckboxChecked('PSD', $defaults, $category, $pitch);?> >.PSD (Photoshop)</label></li>
        <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="Indd" <?php echo extensionsCheckboxChecked('Indd', $defaults, $category, $pitch);?> >.Indd (In Design)</label></li>
        <li class="graysupplement"><label><input type="checkbox" name="" data-value="GIF" <?php echo extensionsCheckboxChecked('GIF', $defaults, $category, $pitch);?> >.GIF</label></li>
        <li class="graysupplement"><label><input type="checkbox" name="" data-value="TIFF" <?php echo extensionsCheckboxChecked('TIFF', $defaults, $category, $pitch);?> >.TIFF</label></li>
        <li class="graysupplement"><label><input type="checkbox" name="" data-value="другие" <?php echo extensionsCheckboxChecked('другие', $defaults, $category, $pitch);?> >другие</label></li>
    </ul><!-- .extensions -->
<?php else: ?>
    <ul class="extensions">
        <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="DOC" <?php echo extensionsCheckboxChecked('DOC', $defaults, $category, $pitch);?> >.DOC</label></li>
        <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="PDF" <?php echo extensionsCheckboxChecked('PDF', $defaults, $category, $pitch);?> >.PDF</label></li>
        <li class="graysupplement"><label><input type="checkbox" name="" data-value="другие" <?php echo extensionsCheckboxChecked('другие', $defaults, $category, $pitch);?> >другие</label></li>
    </ul><!-- .extensions -->
<?php endif;?>