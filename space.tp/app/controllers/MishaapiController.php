<?php
use Phalcon\Mvc\Controller;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Regex as RegexValidator;
use Phalcon\Http\Response;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;

class MishaapiController extends Controller
{
	private function curvl($table, $page)
	{
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://misha.api/'.$table."/".$page);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		return json_decode(curl_exec($curl));
	}

    public function indexAction()
    {
        
    }

    public function selectAction()
    {
        if ($this->request->isPost() == true) 
        {
        	$validation = new Validation();
        	$validation->add('table', new RegexValidator(array(
               'pattern' => '/[а-я\s]{1,30}/u',
               'message' => 'Выберите правильную таблицу<br />'
            )));
            $validation->add('page', new RegexValidator(array(
               'pattern' => '/[0-9]{1,3}/',
               'message' => 'Введите правильно страницу<br />'
            )));
            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {
            	if ($this->request->getPost("table") == "машины") {
            		$out = $this->curvl("car", $this->request->getPost("page"));
            		if ($out->status == "Найдено" && count($out->data) != 0) {
            			?>
            			<table>
						    <tr>
						        <td class="table_header">Марка</td>
						        <td class="table_header">Модель</td>
						        <td class="table_header">Грузоподъёмность</td>
						        <td class="table_header">Водитель</td>
						        <td class="table_header">Владелец</td>
						        <td class="table_header">Изменить</td>
						        <td class="table_header">Удалить</td>
						    </tr>
            			<?
				    	foreach ($out->data as $data) {
				    		?>
				    		<tr>
								<td class="table_body"><?=$data->dealer?></td>
								<td class="table_body"><?=$data->model?></td>
								<td class="table_body"><?=$data->capacity?></td>
								<td class="table_body"><?=$data->driver?></td>
								<td class="table_body"><?=$data->owner?></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=car">Изменить</a></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=car">Удалить</a></td>
							</tr>	
				    		<?
				    	}
				    	?></table><?
				    }
				    else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "марки автомобилей") {
            		$out = $this->curvl("dealer", $this->request->getPost("page"));
            		if ($out->status == "Найдено" && count($out->data) != 0) {
            			?>
            			<table>
						    <tr>
						        <td class="table_header">Имя</td>
						        <td class="table_header">Изменить</td>
						        <td class="table_header">Удалить</td>
						    </tr>
            			<?
				    	foreach ($out->data as $data) {
				    		?>
				    		<tr>
								<td class="table_body"><?=$data->name?></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=dealer">Изменить</a></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=dealer">Удалить</a></td>
							</tr>	
				    		<?
				    	}
				    	?></table><?
				    }
				    else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "водители") {
            		$out = $this->curvl("driver", $this->request->getPost("page"));
            		if ($out->status == "Найдено" && count($out->data) != 0) {
            			?>
            			<table>
						    <tr>
						        <td class="table_header">Имя</td>
						        <td class="table_header">Стаж</td>
						        <td class="table_header">Зарплата</td>
						        <td class="table_header">Изменить</td>
						        <td class="table_header">Удалить</td>
						    </tr>
            			<?
				    	foreach ($out->data as $data) {
				    		?>
				    		<tr>
								<td class="table_body"><?=$data->name?></td>
								<td class="table_body"><?=$data->experience?></td>
								<td class="table_body"><?=$data->salary?></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=driver">Изменить</a></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=driver">Удалить</a></td>
							</tr>	
				    		<?
				    	}
				    	?></table><?
				    }
				    else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "организации") {
            		$out = $this->curvl("organization", $this->request->getPost("page"));
            		if ($out->status == "Найдено" && count($out->data) != 0) {
            			?>
            			<table>
						    <tr>
						        <td class="table_header">Имя</td>
						        <td class="table_header">Адрес</td>
						        <td class="table_header">Изменить</td>
						        <td class="table_header">Удалить</td>
						    </tr>
            			<?
				    	foreach ($out->data as $data) {
				    		?>
				    		<tr>
								<td class="table_body"><?=$data->name?></td>
								<td class="table_body"><?=$data->address?></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=organization">Изменить</a></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=organization">Удалить</a></td>
							</tr>	
				    		<?
				    	}
				    	?></table><?
				    }
				    else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "владельцы") {
            		$out = $this->curvl("owner", $this->request->getPost("page"));
            		if ($out->status == "Найдено" && count($out->data) != 0) {
            			?>
            			<table>
						    <tr>
						        <td class="table_header">Имя</td>
						        <td class="table_header">Изменить</td>
						        <td class="table_header">Удалить</td>
						    </tr>
            			<?
				    	foreach ($out->data as $data) {
				    		?>
				    		<tr>
								<td class="table_body"><?=$data->name?></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=owner">Изменить</a></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=owner">Удалить</a></td>
							</tr>	
				    		<?
				    	}
				    	?></table><?
				    }
				    else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "продукты") {
            		$out = $this->curvl("product", $this->request->getPost("page"));
            		if ($out->status == "Найдено" && count($out->data) != 0) {
            			?>
            			<table>
						    <tr>
						        <td class="table_header">Название</td>
						        <td class="table_header">Вес</td>
						        <td class="table_header">Тип</td>
						        <td class="table_header">Изменить</td>
						        <td class="table_header">Удалить</td>
						    </tr>
            			<?
				    	foreach ($out->data as $data) {
				    		?>
				    		<tr>
								<td class="table_body"><?=$data->name?></td>
								<td class="table_body"><?=$data->weight?></td>
								<td class="table_body"><?=$data->type?></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=product">Изменить</a></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=product">Удалить</a></td>
							</tr>	
				    		<?
				    	}
				    	?></table><?
				    }
				    else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "типы продуктов") {
            		$out = $this->curvl("producttype", $this->request->getPost("page"));
            		if ($out->status == "Найдено" && count($out->data) != 0) {
            			?>
            			<table>
						    <tr>
						        <td class="table_header">Название</td>
						        <td class="table_header">Изменить</td>
						        <td class="table_header">Удалить</td>
						    </tr>
            			<?
				    	foreach ($out->data as $data) {
				    		?>
				    		<tr>
								<td class="table_body"><?=$data->name?></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=producttype">Изменить</a></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=producttype">Удалить</a></td>
							</tr>	
				    		<?
				    	}
				    	?></table><?
				    }
				    else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "груз") {
            		$out = $this->curvl("shipment", $this->request->getPost("page"));
            		if ($out->status == "Найдено" && count($out->data) != 0) {
            			?>
            			<table>
						    <tr>
						    	<td class="table_header">Идентификатор</td>
						        <td class="table_header">Продукт</td>
						        <td class="table_header">Вес</td>
						        <td class="table_header">Тип</td>
						        <td class="table_header">Изменить</td>
						        <td class="table_header">Удалить</td>
						    </tr>
            			<?
				    	foreach ($out->data as $data) {
				    		?>
				    		<tr>
				    			<td class="table_body"><?=$data->id?></td>
								<td class="table_body"><?=$data->product?></td>
								<td class="table_body"><?=$data->transportation_number?></td>
								<td class="table_body"><?=$data->amount?></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=shipment">Изменить</a></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=shipment">Удалить</a></td>
							</tr>	
				    		<?
				    	}
				    	?></table><?
				    }
				    else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "магазины") {
            		$out = $this->curvl("store", $this->request->getPost("page"));
            		if ($out->status == "Найдено" && count($out->data) != 0) {
            			?>
            			<table>
						    <tr>
						        <td class="table_header">Название</td>
						        <td class="table_header">Владелец</td>
						        <td class="table_header">Удалить</td>
						    </tr>
            			<?
				    	foreach ($out->data as $data) {
				    		?>
				    		<tr>
								<td class="table_body"><?=$data->name?></td>
								<td class="table_body"><?=$data->owner?></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=store">Изменить</a></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=store">Удалить</a></td>
							</tr>	
				    		<?
				    	}
				    	?></table><?
				    }
				    else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "перевозки") {
            		$out = $this->curvl("transport", $this->request->getPost("page"));
            		if ($out->status == "Найдено" && count($out->data) != 0) {
            			?>
            			<table>
						    <tr>
						    	<td class="table_header">Идентификатор</td>
						        <td class="table_header">Машина</td>
						        <td class="table_header">Организация</td>
						        <td class="table_header">Магазин</td>
						        <td class="table_header">Дата</td>
						        <td class="table_header">Изменить</td>
						        <td class="table_header">Удалить</td>
						    </tr>
            			<?
				    	foreach ($out->data as $data) {
				    		?>
				    		<tr>
				    			<td class="table_body"><?=$data->id?></td>
								<td class="table_body"><?=$data->car?></td>
								<td class="table_body"><?=$data->organization?></td>
								<td class="table_body"><?=$data->store?></td>
								<td class="table_body"><?=$data->date?></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=transport">Изменить</a></td>
								<td class="table_body"><a href="http://space.tp/mishaapi/update?id=<?=$data->id?>&table=transport">Удалить</a></td>
							</tr>	
				    		<?
				    	}
				    	?></table><?
				    }
				    else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	else {
            		echo "<p>Ой, что-то пошло не так</p>";
            	}
			    

            }
            else
                foreach ($messages as $message) {
                    echo $message;
                }
        }
        else
            echo "Опаньки, поста то нет";

        $this->view->disable();
    }

    public function addAction()
    {

    }

    public function formAction()
    {
    	if ($this->request->isPost() == true) 
        {
        	$validation = new Validation();
        	$validation->add('table', new RegexValidator(array(
               'pattern' => '/[а-я\s]{1,30}/u',
               'message' => 'Выберите правильную таблицу<br />'
            )));
            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {
            	if ($this->request->getPost("table") == "машины") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="car">
						<label>Марка автомобиля</label><br />
						<select name="dealer" class="galaxyin">
							<option disabled="disabled">Выберете Автодилера</option>
            		<?
            		for ($i = 1; $i > 0; $i++) 
            		{ 
            			$out = $this->curvl("dealer", $i);
            			if ($out->status == "Найдено" && count($out->data) != 0)
            			{

		            		foreach ($out->data as $data) {
						    ?>
						    	<option value="<?=$data->id?>"><?=$data->name?></option>
						    <?
						    }
						}
						else
							break;
            		}				    
				    ?>
				    </select>
				    <br />
				    <label>Водитель</label><br />
				    <select name="driver" class="galaxyin">
						<option disabled="disabled">Выберете водителя</option>
					<?
            		for ($i=1; $i > 0; $i++) 
            		{ 
            			$out = $this->curvl("driver", $i);
            			if ($out->status == "Найдено" && count($out->data) != 0)
            			{
		            		foreach ($out->data as $data) {
						    ?>
						    	<option value="<?=$data->id?>"><?=$data->name?></option>
						    <?
						    }
						}
						else
							break;
            		}				    
				    ?>
				    </select>
				    <br />
				    <label>Владелец</label><br />
				    <select name="owner" class="galaxyin">
						<option disabled="disabled">Выберете владельца</option>
					<?
            		for ($i=1; $i > 0; $i++) 
            		{ 
            			$out = $this->curvl("owner", $i);
            			if ($out->status == "Найдено" && count($out->data) != 0)
            			{
		            		foreach ($out->data as $data) {
						    ?>
						    	<option value="<?=$data->id?>"><?=$data->name?></option>
						    <?
						    }
						}
						else
							break;
            		}				    
				    ?>
				    </select>
				    <br />
				    <label>Модель</label><br />
				    <input class="galaxyin" type="text" placeholder="модель" name="model" title="Сюда ввдедите модель" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,49}" required>
				    <br />
				    <label>Грузоподъёмность</label><br />
				    <input class="galaxyin" type="number" placeholder="грузоподъёмность" name="capacity" title="Сюда ввдедите грузоподъёмность" pattern="[0-9]{1,11}" required>
				    <br />
				    <input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
				   
            	}
            	elseif ($this->request->getPost("table") == "марки автомобилей") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="dealer">
						<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required>
				    	<br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "водители") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="driver">
						<label>ФИО</label><br />
				    	<input class="galaxyin" type="text" placeholder="имя" name="name" title="Сюда ввдедите имя" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}" required>
				    	<br />
				    	<label>Стаж с</label><br />
				    	<input class="galaxyin" type="date" placeholder="стаж с" name="experience" title="Сюда ввдедите стаж">
				    	<br />
				    	<label>Зарплата</label><br />
				    	<input class="galaxyin" type="number" placeholder="зарплата" name="salary" title="Сюда ввдедите зарплату" pattern="[0-9]{1,11}" required>
				    	<br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "организации") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="organization">
						<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required><br />
				    	<label>Адрес</label><br />
				    	<input class="galaxyin" type="text" placeholder="адрес" name="address" title="Сюда ввдедите адрес" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,99}" required><br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "владельцы") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="owner">
						<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}" required><br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "продукты") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="product">
						<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required><br />
				    	<label>Вес</label><br />
				    	<input class="galaxyin" type="number" placeholder="вес" name="salary" title="Сюда ввдедите вес" pattern="[0-9]{1,11}" required><br />
				    	
					    <label>Тип</label><br />
					    <select name="producttype" class="galaxyin">
							<option disabled="disabled">Выберете тип</option>
						<?
	            		for ($i=1; $i > 0; $i++) 
	            		{ 
	            			$out = $this->curvl("producttype", $i);
	            			if ($out->status == "Найдено" && count($out->data) != 0)
	            			{
			            		foreach ($out->data as $data) {
							    ?>
							    	<option value="<?=$data->id?>"><?=$data->name?></option>
							    <?
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
					    <input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "типы продуктов") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="producttype">
						<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required>
				    	<br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "груз") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="shipment">
						<label>Продукт</label><br />
					    <select name="product" class="galaxyin">
							<option disabled="disabled">Выберете Продукт</option>
						<?
	            		for ($i=1; $i > 0; $i++) 
	            		{ 
	            			$out = $this->curvl("product", $i);
	            			if ($out->status == "Найдено" && count($out->data) != 0)
	            			{
			            		foreach ($out->data as $data) {
							    ?>
							    	<option value="<?=$data->id?>"><?=$data->name?></option>
							    <?
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
					    <label>Перевозка</label><br />
					    <select name="transport" class="galaxyin">
							<option disabled="disabled">Выберете перевозку</option>
						<?
	            		for ($i=1; $i > 0; $i++) 
	            		{ 
	            			$out = $this->curvl("transport", $i);
	            			if ($out->status == "Найдено" && count($out->data) != 0)
	            			{
			            		foreach ($out->data as $data) {
							    ?>
							    	<option value="<?=$data->id?>"><?=$data->id?></option>
							    <?
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
					    <label>Кол-во</label><br />
				    	<input class="galaxyin" type="number" placeholder="кол-во" name="amount" title="Сюда ввдедите кол-во" pattern="[0-9]{1,11}" required>
				    	<br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form>
				    <?
            	}
            	elseif ($this->request->getPost("table") == "магазины") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="store">
						<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required>
				    	<br />
				    	<label>Владелец</label><br />
					    <select name="owner" class="galaxyin">
							<option disabled="disabled">Выберете владельца</option>
						<?
	            		for ($i=1; $i > 0; $i++) 
	            		{ 
	            			$out = $this->curvl("owner", $i);
	            			if ($out->status == "Найдено" && count($out->data) != 0)
	            			{
			            		foreach ($out->data as $data) {
							    ?>
							    	<option value="<?=$data->id?>"><?=$data->name?></option>
							    <?
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form>
				    <?
            	}
            	elseif ($this->request->getPost("table") == "перевозки") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="transport">
						<label>Автомобиль</label><br />
					    <select name="car" class="galaxyin">
							<option disabled="disabled">Выберете автомобиль</option>
						<?
	            		for ($i=1; $i > 0; $i++) 
	            		{ 
	            			$out = $this->curvl("car", $i);
	            			if ($out->status == "Найдено" && count($out->data) != 0)
	            			{
			            		foreach ($out->data as $data) {
							    ?>
							    	<option value="<?=$data->id?>"><?=$data->model?></option>
							    <?
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
					    <label>Организация</label><br />
					    <select name="organization" class="galaxyin">
							<option disabled="disabled">Выберете организацию</option>
						<?
	            		for ($i=1; $i > 0; $i++) 
	            		{ 
	            			$out = $this->curvl("organization", $i);
	            			if ($out->status == "Найдено" && count($out->data) != 0)
	            			{
			            		foreach ($out->data as $data) {
							    ?>
							    	<option value="<?=$data->id?>"><?=$data->name?></option>
							    <?
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
					    <label>Магазин</label><br />
					    <select name="store" class="galaxyin">
							<option disabled="disabled">Выберете Магазин</option>
						<?
	            		for ($i=1; $i > 0; $i++) 
	            		{ 
	            			$out = $this->curvl("store", $i);
	            			if ($out->status == "Найдено" && count($out->data) != 0)
	            			{
			            		foreach ($out->data as $data) {
							    ?>
							    	<option value="<?=$data->id?>"><?=$data->name?></option>
							    <?
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form>
				    <?
            	}
            	else {
            		echo "<p>Ой, что-то пошло не так</p>";
            	}
			    

            }
            else
                foreach ($messages as $message) {
                    echo $message;
                }
        }
        else
            echo "Опаньки, поста то нет";

        $this->view->disable();
    }

    public function insertAction()
    {
    	if ($this->request->isPost() == true) 
        {
        	/*$validation = new Validation();
        	$validation->add('table', new RegexValidator(array(
               'pattern' => '/[а-я\s]{1,30}/u',
               'message' => 'Выберите правильную таблицу<br />'
            )));*/
            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {
            	if ($this->request->getPost("table") == "машины") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="car">
						<label>Марка автомобиля</label><br />
						<select name="dealer" class="galaxyin">
							<option disabled="disabled">Выберете Автодилера</option>
            		<?
            		for ($i = 1; $i > 0; $i++) 
            		{ 
            			$out = $this->curvl("dealer", $i);
            			if ($out->status == "Найдено" && count($out->data) != 0)
            			{

		            		foreach ($out->data as $data) {
						    ?>
						    	<option value="<?=$data->id?>"><?=$data->name?></option>
						    <?
						    }
						}
						else
							break;
            		}				    
				    ?>
				    </select>
				    <br />
				    <label>Водитель</label><br />
				    <select name="driver" class="galaxyin">
						<option disabled="disabled">Выберете водителя</option>
					<?
            		for ($i=1; $i > 0; $i++) 
            		{ 
            			$out = $this->curvl("driver", $i);
            			if ($out->status == "Найдено" && count($out->data) != 0)
            			{
		            		foreach ($out->data as $data) {
						    ?>
						    	<option value="<?=$data->id?>"><?=$data->name?></option>
						    <?
						    }
						}
						else
							break;
            		}				    
				    ?>
				    </select>
				    <br />
				    <label>Владелец</label><br />
				    <select name="owner" class="galaxyin">
						<option disabled="disabled">Выберете владельца</option>
					<?
            		for ($i=1; $i > 0; $i++) 
            		{ 
            			$out = $this->curvl("owner", $i);
            			if ($out->status == "Найдено" && count($out->data) != 0)
            			{
		            		foreach ($out->data as $data) {
						    ?>
						    	<option value="<?=$data->id?>"><?=$data->name?></option>
						    <?
						    }
						}
						else
							break;
            		}				    
				    ?>
				    </select>
				    <br />
				    <label>Модель</label><br />
				    <input class="galaxyin" type="text" placeholder="модель" name="model" title="Сюда ввдедите модель" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,49}" required>
				    <br />
				    <label>Грузоподъёмность</label><br />
				    <input class="galaxyin" type="number" placeholder="грузоподъёмность" name="capacity" title="Сюда ввдедите грузоподъёмность" pattern="[0-9]{1,11}" required>
				    <br />
				    <input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
				   
            	}
            	elseif ($this->request->getPost("table") == "марки автомобилей") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="dealer">
						<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required>
				    	<br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "водители") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="driver">
						<label>ФИО</label><br />
				    	<input class="galaxyin" type="text" placeholder="имя" name="name" title="Сюда ввдедите имя" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}" required>
				    	<br />
				    	<label>Стаж с</label><br />
				    	<input class="galaxyin" type="date" placeholder="стаж с" name="experience" title="Сюда ввдедите стаж">
				    	<br />
				    	<label>Зарплата</label><br />
				    	<input class="galaxyin" type="number" placeholder="зарплата" name="salary" title="Сюда ввдедите зарплату" pattern="[0-9]{1,11}" required>
				    	<br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "организации") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="organization">
						<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required><br />
				    	<label>Адрес</label><br />
				    	<input class="galaxyin" type="text" placeholder="адрес" name="address" title="Сюда ввдедите адрес" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,99}" required><br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "владельцы") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="owner">
						<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}" required><br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "продукты") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="product">
						<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required><br />
				    	<label>Вес</label><br />
				    	<input class="galaxyin" type="number" placeholder="вес" name="salary" title="Сюда ввдедите вес" pattern="[0-9]{1,11}" required><br />
				    	
					    <label>Тип</label><br />
					    <select name="producttype" class="galaxyin">
							<option disabled="disabled">Выберете тип</option>
						<?
	            		for ($i=1; $i > 0; $i++) 
	            		{ 
	            			$out = $this->curvl("producttype", $i);
	            			if ($out->status == "Найдено" && count($out->data) != 0)
	            			{
			            		foreach ($out->data as $data) {
							    ?>
							    	<option value="<?=$data->id?>"><?=$data->name?></option>
							    <?
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
					    <input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "типы продуктов") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="producttype">
						<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required>
				    	<br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "груз") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="shipment">
						<label>Продукт</label><br />
					    <select name="product" class="galaxyin">
							<option disabled="disabled">Выберете Продукт</option>
						<?
	            		for ($i=1; $i > 0; $i++) 
	            		{ 
	            			$out = $this->curvl("product", $i);
	            			if ($out->status == "Найдено" && count($out->data) != 0)
	            			{
			            		foreach ($out->data as $data) {
							    ?>
							    	<option value="<?=$data->id?>"><?=$data->name?></option>
							    <?
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
					    <label>Перевозка</label><br />
					    <select name="transport" class="galaxyin">
							<option disabled="disabled">Выберете перевозку</option>
						<?
	            		for ($i=1; $i > 0; $i++) 
	            		{ 
	            			$out = $this->curvl("transport", $i);
	            			if ($out->status == "Найдено" && count($out->data) != 0)
	            			{
			            		foreach ($out->data as $data) {
							    ?>
							    	<option value="<?=$data->id?>"><?=$data->id?></option>
							    <?
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
					    <label>Кол-во</label><br />
				    	<input class="galaxyin" type="number" placeholder="кол-во" name="amount" title="Сюда ввдедите кол-во" pattern="[0-9]{1,11}" required>
				    	<br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form>
				    <?
            	}
            	elseif ($this->request->getPost("table") == "магазины") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="store">
						<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required>
				    	<br />
				    	<label>Владелец</label><br />
					    <select name="owner" class="galaxyin">
							<option disabled="disabled">Выберете владельца</option>
						<?
	            		for ($i=1; $i > 0; $i++) 
	            		{ 
	            			$out = $this->curvl("owner", $i);
	            			if ($out->status == "Найдено" && count($out->data) != 0)
	            			{
			            		foreach ($out->data as $data) {
							    ?>
							    	<option value="<?=$data->id?>"><?=$data->name?></option>
							    <?
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form>
				    <?
            	}
            	elseif ($this->request->getPost("table") == "перевозки") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/inser" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="transport">
						<label>Автомобиль</label><br />
					    <select name="car" class="galaxyin">
							<option disabled="disabled">Выберете автомобиль</option>
						<?
	            		for ($i=1; $i > 0; $i++) 
	            		{ 
	            			$out = $this->curvl("car", $i);
	            			if ($out->status == "Найдено" && count($out->data) != 0)
	            			{
			            		foreach ($out->data as $data) {
							    ?>
							    	<option value="<?=$data->id?>"><?=$data->model?></option>
							    <?
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
					    <label>Организация</label><br />
					    <select name="organization" class="galaxyin">
							<option disabled="disabled">Выберете организацию</option>
						<?
	            		for ($i=1; $i > 0; $i++) 
	            		{ 
	            			$out = $this->curvl("organization", $i);
	            			if ($out->status == "Найдено" && count($out->data) != 0)
	            			{
			            		foreach ($out->data as $data) {
							    ?>
							    	<option value="<?=$data->id?>"><?=$data->name?></option>
							    <?
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
					    <label>Магазин</label><br />
					    <select name="store" class="galaxyin">
							<option disabled="disabled">Выберете Магазин</option>
						<?
	            		for ($i=1; $i > 0; $i++) 
	            		{ 
	            			$out = $this->curvl("store", $i);
	            			if ($out->status == "Найдено" && count($out->data) != 0)
	            			{
			            		foreach ($out->data as $data) {
							    ?>
							    	<option value="<?=$data->id?>"><?=$data->name?></option>
							    <?
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form>
				    <?
            	}
            	else {
            		echo "<p>Ой, что-то пошло не так</p>";
            	}
			    

            }
            else
                foreach ($messages as $message) {
                    echo $message;
                }
        }
        else
            echo "Опаньки, поста то нет";

        $this->view->disable();
    }



}
?>