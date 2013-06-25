<div class="wrapper">
<?=$this->view()->render(array('element' => 'header'))?>
	<div class="conteiner">
	<?=$this->form->create($pitch)?>
	<div>
	<?=$this->form->label('PitchTitle', 'Название')?>
	<?=$this->form->text('title')?>
	</div><div>
	<?=$this->form->label('PitchCategoryId', 'Категория')?>
	<?=$this->form->text('category_id')?>
	</div><div>
	<?=$this->form->label('PitchIndustry', 'Индустрия')?>
	<?=$this->form->text('industry')?>
	</div><div>
	<?=$this->form->label('PitchDescription', 'Описание')?>
	<?=$this->form->textarea('description')?>
	</div><div>
	<?=$this->form->label('PitchStarted', 'Создан')?>
	<?=$this->form->text('started')?>
	</div><div>
	<?=$this->form->label('PitchIdeasCount', 'Кол-во идей')?>
	<?=$this->form->text('ideas_count')?>
	</div><div>
	<?=$this->form->label('PitchPrice', 'Цена')?>
	<?=$this->form->text('price')?>
	</div><div>
	<?=$this->form->submit('save')?>
	<?=$this->form->end()?>
	</div>
</div>