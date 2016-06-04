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
	private function curvl_id($table, $id)
	{
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://misha.api/'.$table."/search/id/".$id);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		return json_decode(curl_exec($curl));
	}
	private function curvl_add($data, $table)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://misha.api/'.$table);
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	    return  json_decode(curl_exec($curl));
	}

	private function curvl_upd($data, $table, $id)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://misha.api/'.$table."/".$id);
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");                                                                     
	    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	    return  json_decode(curl_exec($curl));
	}

	private function curvl_del($table, $id)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://misha.api/'.$table."/".$id);
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");                                                                     
	    //curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	    return  json_decode(curl_exec($curl));
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
								<td class="table_body">
									<form name="update" action="http://space.tp/mishaapi/update" method="post">
										<input class="galaxyin" type="hidden" name="table" value="car">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">										
										<input class="galaxybutton" name="upd" type="submit" value="Изменить">
									</form>
								</td>
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/delete" method="post">
										<input class="galaxyin" type="hidden" name="table" value="car">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">										
										<input class="galaxybutton" name="upd" type="submit" value="Удалить">
									</form></td>
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
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/update" method="post">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">
										<input class="galaxyin" type="hidden" name="table" value="dealer">
										<input class="galaxybutton" name="upd" type="submit" value="Изменить">
									</form></td>
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/delete" method="post">
										<input class="galaxyin" type="hidden" name="table" value="dealer">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">										
										<input class="galaxybutton" name="upd" type="submit" value="Удалить">
									</form></td>
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
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/update" method="post">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">
										<input class="galaxyin" type="hidden" name="table" value="driver">
										<input class="galaxybutton" name="upd" type="submit" value="Изменить">
									</form></td>
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/delete" method="post">
										<input class="galaxyin" type="hidden" name="table" value="driver">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">										
										<input class="galaxybutton" name="upd" type="submit" value="Удалить">
									</form></td>
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
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/update" method="post">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">
										<input class="galaxyin" type="hidden" name="table" value="organization">
										<input class="galaxybutton" name="upd" type="submit" value="Изменить">
									</form></td>
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/delete" method="post">
										<input class="galaxyin" type="hidden" name="table" value="organization">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">										
										<input class="galaxybutton" name="upd" type="submit" value="Удалить">
									</form></td>
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
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/update" method="post">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">
										<input class="galaxyin" type="hidden" name="table" value="owner">
										<input class="galaxybutton" name="upd" type="submit" value="Изменить">
									</form></td>
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/delete" method="post">
										<input class="galaxyin" type="hidden" name="table" value="owner">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">										
										<input class="galaxybutton" name="upd" type="submit" value="Удалить">
									</form></td>
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
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/update" method="post">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">
										<input class="galaxyin" type="hidden" name="table" value="product">
										<input class="galaxybutton" name="upd" type="submit" value="Изменить">
									</form></td>
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/delete" method="post">
										<input class="galaxyin" type="hidden" name="table" value="product">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">										
										<input class="galaxybutton" name="upd" type="submit" value="Удалить">
									</form></td>
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
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/update" method="post">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">
										<input class="galaxyin" type="hidden" name="table" value="producttype">
										<input class="galaxybutton" name="upd" type="submit" value="Изменить">
									</form></td>
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/delete" method="post">
										<input class="galaxyin" type="hidden" name="table" value="producttype">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">										
										<input class="galaxybutton" name="upd" type="submit" value="Удалить">
									</form></td>
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
						        <td class="table_header">Перевозка</td>
						        <td class="table_header">Вес</td>
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
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/update" method="post">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">
										<input class="galaxyin" type="hidden" name="table" value="shipment">
										<input class="galaxybutton" name="upd" type="submit" value="Изменить">
									</form></td>
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/delete" method="post">
										<input class="galaxyin" type="hidden" name="table" value="shipment">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">										
										<input class="galaxybutton" name="upd" type="submit" value="Удалить">
									</form></td>
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
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/update" method="post">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">
										<input class="galaxyin" type="hidden" name="table" value="store">
										<input class="galaxybutton" name="upd" type="submit" value="Изменить">
									</form></td>
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/delete" method="post">
										<input class="galaxyin" type="hidden" name="table" value="store">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">										
										<input class="galaxybutton" name="upd" type="submit" value="Удалить">
									</form></td>
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
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/update" method="post">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">
										<input class="galaxyin" type="hidden" name="table" value="transport">
										<input class="galaxybutton" name="upd" type="submit" value="Изменить">
									</form></td>
								<td class="table_body"><form name="update" action="http://space.tp/mishaapi/delete" method="post">
										<input class="galaxyin" type="hidden" name="table" value="transport">
										<input class="galaxyin" type="hidden" name="id" value="<?=$data->id?>">										
										<input class="galaxybutton" name="upd" type="submit" value="Удалить">
									</form></td>
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

    public function formpostAction()
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
            		<form name="fupd" action="http://space.tp/mishaapi/insert" method="post">
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
            		<form name="fupd" action="http://space.tp/mishaapi/insert" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="dealer">
            			<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required>
				    	<br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "водители") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/insert" method="post">
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
            		<form name="fupd" action="http://space.tp/mishaapi/insert" method="post">
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
            		<form name="fupd" action="http://space.tp/mishaapi/insert" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="owner">
            			<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}" required><br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "продукты") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/insert" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="product">
            			<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required><br />
				    	<label>Вес</label><br />
				    	<input class="galaxyin" type="number" placeholder="вес" name="weight" title="Сюда ввдедите вес" pattern="[0-9]{1,11}" required><br />
				    	
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
            		<form name="fupd" action="http://space.tp/mishaapi/insert" method="post">
            			<input class="galaxyin" type="hidden" name="table" value="producttype">
            			<label>Название</label><br />
				    	<input class="galaxyin" type="text" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required>
				    	<br />
				    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
				    </form><?
            	}
            	elseif ($this->request->getPost("table") == "груз") {
            		?>
            		<form name="fupd" action="http://space.tp/mishaapi/insert" method="post">
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
            		<form name="fupd" action="http://space.tp/mishaapi/insert" method="post">
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
            		<form name="fupd" action="http://space.tp/mishaapi/insert" method="post">
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
					    <label>Дата</label><br />
				    	<input class="galaxyin" type="date" placeholder="дата" name="date" title="Сюда ввдедите дату">
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
        	$validation = new Validation();
        	$validation->add('table', new RegexValidator(array(
               'pattern' => '/[a-z\s]{1,30}/u',
               'message' => 'Выберите правильную таблицу<br />'
            )));
            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {
            	if ($this->request->getPost("table") == "car") {
            		$validation->add('dealer', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID автопроизводителя не верен<br />'
		            )));	
		            $validation->add('driver', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID водителя не верен<br />'
		            )));	
		            $validation->add('owner', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID владельца не верен<br />'
		            )));	
		            $validation->add('model', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,49}/u',
		               'message' => 'Модель не корректна<br />'
		            )));
		            $validation->add('capacity', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'Грузоподъёмность не корректна<br />'
		            )));
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_add(array('dealer_id' => $this->request->getPost("dealer"), 'driver_id' => $this->request->getPost("driver"), 'owner_id' => $this->request->getPost("owner"), 'model' => $this->request->getPost("model"), 'capacity' => $this->request->getPost("capacity")), "car")->status == 'CREATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }		   
            	}
            	elseif ($this->request->getPost("table") == "dealer") {	
		            $validation->add('name', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,49}/u',
		               'message' => 'Название не корректно<br />'
		            )));
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_add(array('name' => $this->request->getPost("name")), "dealer")->status == 'CREATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	elseif ($this->request->getPost("table") == "driver") {
            		$validation->add('name', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}/u',
		               'message' => 'Название не корректно<br />'
		            )));	
		            $validation->add('salary', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'Зарплата не корректна<br />'
		            )));		            
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_add(array('name' => $this->request->getPost("name"), 'experience' => $this->request->getPost("experience"), 'salary' => $this->request->getPost("salary")), "driver")->status == 'CREATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }		
            	}
            	elseif ($this->request->getPost("table") == "organization") {
            		$validation->add('name', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}/u',
		               'message' => 'Название не корректно<br />'
		            )));
		            $validation->add('address', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,99}/u',
		               'message' => 'Адрес не корректен<br />'
		            )));		
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_add(array('name' => $this->request->getPost("name"), 'address' => $this->request->getPost("address")), "organization")->status == 'CREATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	elseif ($this->request->getPost("table") == "owner") {
            		$validation->add('name', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}/u',
		               'message' => 'Название не корректно<br />'
		            )));
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_add(array('name' => $this->request->getPost("name")), "owner")->status == 'CREATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	elseif ($this->request->getPost("table") == "product") {
            		$validation->add('name', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}/u',
		               'message' => 'Название не корректно<br />'
		            )));
		            $validation->add('weight', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'Вес не корректен<br />'
		            )));
		            $validation->add('producttype', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID типа не верен<br />'
		            )));
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_add(array('name' => $this->request->getPost("name"), 'weight' => $this->request->getPost("weight"), 'type_id' => $this->request->getPost("producttype")), "product")->status == 'CREATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	elseif ($this->request->getPost("table") == "producttype") {
            		$validation->add('name', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}/u',
		               'message' => 'Название не корректно<br />'
		            )));
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_add(array('name' => $this->request->getPost("name")), "producttype")->status == 'CREATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	elseif ($this->request->getPost("table") == "shipment") {
            		$validation->add('product', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID продукта не верен<br />'
		            )));	
		            $validation->add('transport', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID перевозки не верен<br />'
		            )));	
		            $validation->add('amount', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'вес не корректен<br />'
		            )));
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_add(array('product_id' => $this->request->getPost("product"), 'transportation_id' => $this->request->getPost("transport"), 'amount' => $this->request->getPost("amount")), "shipment")->status == 'CREATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	elseif ($this->request->getPost("table") == "store") {
            		$validation->add('name', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}/u',
		               'message' => 'Название не корректно<br />'
		            )));	
		            $validation->add('owner', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID владельца не верен<br />'
		            )));
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_add(array('name' => $this->request->getPost("name"), 'owner_id' => $this->request->getPost("owner")), "store")->status == 'CREATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	elseif ($this->request->getPost("table") == "transport") {
            		$validation->add('car', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID автомобиля не верен<br />'
		            )));	
		            $validation->add('organization', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID организации не верен<br />'
		            )));	
		            $validation->add('store', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID магазина не верен<br />'
		            )));	
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_add(array('car_id' => $this->request->getPost("car"), 'organization_id' => $this->request->getPost("organization"), 'store_id' => $this->request->getPost("store"), 'date' => $this->request->getPost("date")), "transport")->status == 'CREATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	else {
            		echo "<p>Ой, что-то пошло не так</p>";
            	}
			    

            }
            else
            {
            	$this->view->messages = $messages;
                
            }
        }
        else
            $this->view->result = "Опаньки, поста то нет";
    }

    public function updateAction()
    {
    	if ($this->request->isPost() == true) 
        {
        	$validation = new Validation();
        	$validation->add('table', new RegexValidator(array(
               'pattern' => '/[a-z\s]{1,30}/u',
               'message' => 'Выберите правильную таблицу<br />'
            )));
            $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,11}/u',
               'message' => 'ID параметр не верен<br />'
            )));
           $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {
            	if ($this->request->getPost("table") == "car") {
	            	$this->view->table = "car";
	            	$this->view->id = $this->request->getPost("id");            			
				    
            	}
            	elseif ($this->request->getPost("table") == "dealer") {
            		$this->view->table = "dealer";
	            	$this->view->id = $this->request->getPost("id");   
            	}
            	elseif ($this->request->getPost("table") == "driver") {
            		$this->view->table = "driver";
	            	$this->view->id = $this->request->getPost("id");  
            	}
            	elseif ($this->request->getPost("table") == "organization") {
            		$this->view->table = "organization";
	            	$this->view->id = $this->request->getPost("id");  
            	}
            	elseif ($this->request->getPost("table") == "owner") {
            		$this->view->table = "owner";
	            	$this->view->id = $this->request->getPost("id");  
            	}
            	elseif ($this->request->getPost("table") == "product") {
            		$this->view->table = "product";
	            	$this->view->id = $this->request->getPost("id");  
            	}
            	elseif ($this->request->getPost("table") == "producttype") {
            		$this->view->table = "producttype";
	            	$this->view->id = $this->request->getPost("id");  
            	}
            	elseif ($this->request->getPost("table") == "shipment") {
            		$this->view->table = "shipment";
	            	$this->view->id = $this->request->getPost("id");  
            	}
            	elseif ($this->request->getPost("table") == "store") {
            		$this->view->table = "store";
	            	$this->view->id = $this->request->getPost("id"); 
            	}
            	elseif ($this->request->getPost("table") == "transport") {
            		$this->view->table = "transport";
	            	$this->view->id = $this->request->getPost("id"); 
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
    }

    public function updedAction()
    {
    	if ($this->request->isPost() == true) 
        {
        	$validation = new Validation();
        	$validation->add('table', new RegexValidator(array(
               'pattern' => '/[a-z\s]{1,30}/u',
               'message' => 'Выберите правильную таблицу<br />'
            )));
            $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,11}/u',
               'message' => 'ID параметра сдесь нет<br />'
            )));
            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {
            	if ($this->request->getPost("table") == "car") {
            		$validation->add('dealer', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID автопроизводителя не верен<br />'
		            )));	
		            $validation->add('driver', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID водителя не верен<br />'
		            )));	
		            $validation->add('owner', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID владельца не верен<br />'
		            )));	
		            $validation->add('model', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,49}/u',
		               'message' => 'Модель не корректна<br />'
		            )));
		            $validation->add('capacity', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'Грузоподъёмность не корректна<br />'
		            )));
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_upd(array('dealer_id' => $this->request->getPost("dealer"), 'driver_id' => $this->request->getPost("driver"), 'owner_id' => $this->request->getPost("owner"), 'model' => $this->request->getPost("model"), 'capacity' => $this->request->getPost("capacity")), "car", $this->request->getPost("id"))->status == 'UPDATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }		   
            	}
            	elseif ($this->request->getPost("table") == "dealer") {	
		            $validation->add('name', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,49}/u',
		               'message' => 'Название не корректно<br />'
		            )));
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_upd(array('name' => $this->request->getPost("name")), "dealer", $this->request->getPost("id"))->status == 'UPDATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	elseif ($this->request->getPost("table") == "driver") {
            		$validation->add('name', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}/u',
		               'message' => 'Название не корректно<br />'
		            )));	
		            $validation->add('salary', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'Зарплата не корректна<br />'
		            )));		            
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_upd(array('name' => $this->request->getPost("name"), 'experience' => $this->request->getPost("experience"), 'salary' => $this->request->getPost("salary")), "driver", $this->request->getPost("id"))->status == 'UPDATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }		
            	}
            	elseif ($this->request->getPost("table") == "organization") {
            		$validation->add('name', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}/u',
		               'message' => 'Название не корректно<br />'
		            )));
		            $validation->add('address', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,99}/u',
		               'message' => 'Адрес не корректен<br />'
		            )));		
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_upd(array('name' => $this->request->getPost("name"), 'address' => $this->request->getPost("address")), "organization", $this->request->getPost("id"))->status == 'UPDATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	elseif ($this->request->getPost("table") == "owner") {
            		$validation->add('name', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}/u',
		               'message' => 'Название не корректно<br />'
		            )));
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_upd(array('name' => $this->request->getPost("name")), "owner", $this->request->getPost("id"))->status == 'UPDATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	elseif ($this->request->getPost("table") == "product") {
            		$validation->add('name', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}/u',
		               'message' => 'Название не корректно<br />'
		            )));
		            $validation->add('weight', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'Вес не корректен<br />'
		            )));
		            $validation->add('producttype', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID типа не верен<br />'
		            )));
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_upd(array('name' => $this->request->getPost("name"), 'weight' => $this->request->getPost("weight"), 'type_id' => $this->request->getPost("producttype")), "product", $this->request->getPost("id"))->status == 'UPDATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	elseif ($this->request->getPost("table") == "producttype") {
            		$validation->add('name', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}/u',
		               'message' => 'Название не корректно<br />'
		            )));
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_upd(array('name' => $this->request->getPost("name")), "producttype", $this->request->getPost("id"))->status == 'UPDATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	elseif ($this->request->getPost("table") == "shipment") {
            		$validation->add('product', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID продукта не верен<br />'
		            )));	
		            $validation->add('transport', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID перевозки не верен<br />'
		            )));	
		            $validation->add('amount', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'вес не корректен<br />'
		            )));
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_upd(array('product_id' => $this->request->getPost("product"), 'transportation_id' => $this->request->getPost("transport"), 'amount' => $this->request->getPost("amount")), "shipment", $this->request->getPost("id"))->status == 'UPDATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	elseif ($this->request->getPost("table") == "store") {
            		$validation->add('name', new RegexValidator(array(
		               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}/u',
		               'message' => 'Название не корректно<br />'
		            )));	
		            $validation->add('owner', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID владельца не верен<br />'
		            )));
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_upd(array('name' => $this->request->getPost("name"), 'owner_id' => $this->request->getPost("owner")), "store", $this->request->getPost("id"))->status == 'UPDATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	elseif ($this->request->getPost("table") == "transport") {
            		$validation->add('car', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID автомобиля не верен<br />'
		            )));	
		            $validation->add('organization', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID организации не верен<br />'
		            )));	
		            $validation->add('store', new RegexValidator(array(
		               'pattern' => '/[0-9]{1,11}/u',
		               'message' => 'ID магазина не верен<br />'
		            )));	
		            $messages = $validation->validate($_POST);
		            if (!count($messages)) 
		            {
		            	if ($this->curvl_upd(array('car_id' => $this->request->getPost("car"), 'organization_id' => $this->request->getPost("organization"), 'store_id' => $this->request->getPost("store"), 'date' => $this->request->getPost("date")), "transport", $this->request->getPost("id"))->status == 'UPDATED')
							$this->view->result = "Успешно";						
						else
						{
							$this->view->result = "Неудачно";
						}
		            }
		            else
		            {
		            	$this->view->messages = $messages;
	                }	
            	}
            	else {
            		echo "<p>Ой, что-то пошло не так</p>";
            	}
			    

            }
            else
            {
            	$this->view->messages = $messages;
                
            }
        }
        else
            $this->view->result = "Опаньки, поста то нет";
    }

	public function formupdAction()
    {
    	if ($this->request->isPost() == true) 
        {
        	$validation = new Validation();
        	$validation->add('table', new RegexValidator(array(
               'pattern' => '/[a-z\s]{1,30}/u',
               'message' => 'Выберите правильную таблицу<br />'
            )));
            $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,11}/u',
               'message' => 'ID параметр не верен<br />'
            )));
            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {
            	if ($this->request->getPost("table") == "car") {
            		$out = $this->curvl_id("car", $this->request->getPost("id"));
            		if ($out->status == "Найдено" && count($out->data) != 0) {
            			$data_g = $out->data;
            			?>
	            		<form name="fupd" action="http://space.tp/mishaapi/upded" method="post">
	            			<input class="galaxyin" type="hidden" name="table" value="car">
	            			<input class="galaxyin" type="hidden" name="id" value="<?=$data_g->id?>">
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
			            			if ($data_g->dealer == $data->name) {
			            				?>
									    	<option selected="selected" value="<?=$data->id?>"><?=$data->name?></option>
									    <?
			            			}
			            			else
			            			{
									    ?>
									    	<option value="<?=$data->id?>"><?=$data->name?></option>
									    <?
									}
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
							    	if ($data_g->driver == $data->name) {
			            				?>
									    	<option selected="selected" value="<?=$data->id?>"><?=$data->name?></option>
									    <?
			            			}
			            			else
			            			{
									    ?>
									    	<option value="<?=$data->id?>"><?=$data->name?></option>
									    <?
									}
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
							    	if ($data_g->owner == $data->name) {
			            				?>
									    	<option selected="selected" value="<?=$data->id?>"><?=$data->name?></option>
									    <?
			            			}
			            			else
			            			{
									    ?>
									    	<option value="<?=$data->id?>"><?=$data->name?></option>
									    <?
									}
							    }
							}
							else
								break;
	            		}				    
					    ?>
					    </select>
					    <br />
					    <label>Модель</label><br />
					    <input class="galaxyin" type="text" placeholder="модель" value="<?=$data_g->model;?>" name="model" title="Сюда ввдедите модель" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,49}" required>
					    <br />
					    <label>Грузоподъёмность</label><br />
					    <input class="galaxyin" type="number" placeholder="грузоподъёмность" value="<?=$data_g->capacity;?>" name="capacity" title="Сюда ввдедите грузоподъёмность" pattern="[0-9]{1,11}" required>
					    <br />
					    <input class="galaxybutton" name="add" type="submit" value="Добавить">
					    </form><?
				    }
				    else
				    {
				    	echo "Ничего не найдено";
				    }
				   
            	}
            	elseif ($this->request->getPost("table") == "dealer") {
            		$out = $this->curvl_id("dealer", $this->request->getPost("id"));
            		if ($out->status == "Найдено" && count($out->data) != 0){
            			$data_g = $out->data;
	            		?>
	            		<form name="fupd" action="http://space.tp/mishaapi/upded" method="post">
	            			<input class="galaxyin" type="hidden" name="table" value="dealer">
	            			<input class="galaxyin" type="hidden" name="id" value="<?=$data_g->id?>">
	            			<label>Название</label><br />
					    	<input class="galaxyin" type="text" value="<?=$data_g->name?>" placeholder="название" name="name" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required>
					    	<br />
					    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
					    </form><?
					}
					else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "driver") {
            		$out = $this->curvl_id("driver", $this->request->getPost("id"));
            		if ($out->status == "Найдено" && count($out->data) != 0){
            			$data_g = $out->data;
	            		?>
	            		<form name="fupd" action="http://space.tp/mishaapi/upded" method="post">
	            			<input class="galaxyin" type="hidden" name="table" value="driver">
	            			<input class="galaxyin" type="hidden" name="id" value="<?=$data_g->id?>">
	            			<label>ФИО</label><br />
					    	<input class="galaxyin" type="text" placeholder="имя" value="<?=$data_g->name?>" name="name" title="Сюда ввдедите имя" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}" required>
					    	<br />
					    	<label>Стаж с</label><br />
					    	<input class="galaxyin" type="date" placeholder="стаж с" value="<?=$data_g->experience?>" name="experience" title="Сюда ввдедите стаж">
					    	<br />
					    	<label>Зарплата</label><br />
					    	<input class="galaxyin" type="number" placeholder="зарплата" name="salary" value="<?=$data_g->salary?>" title="Сюда ввдедите зарплату" pattern="[0-9]{1,11}" required>
					    	<br />
					    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
					    </form><?
				    }
					else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "organization") {
            		$out = $this->curvl_id("organization", $this->request->getPost("id"));
            		if ($out->status == "Найдено" && count($out->data) != 0){
            			$data_g = $out->data;
	            		?>
	            		<form name="fupd" action="http://space.tp/mishaapi/upded" method="post">
	            			<input class="galaxyin" type="hidden" name="table" value="organization">
	            			<input class="galaxyin" type="hidden" name="id" value="<?=$data_g->id?>">
	            			<label>Название</label><br />
					    	<input class="galaxyin" type="text" placeholder="название" name="name" value="<?=$data_g->name?>" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required><br />
					    	<label>Адрес</label><br />
					    	<input class="galaxyin" type="text" placeholder="адрес" name="address" value="<?=$data_g->address?>" title="Сюда ввдедите адрес" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,99}" required><br />
					    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
					    </form><?
				    }
					else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "owner") {
            		$out = $this->curvl_id("owner", $this->request->getPost("id"));
            		if ($out->status == "Найдено" && count($out->data) != 0){
            			$data_g = $out->data;
	            		?>
	            		<form name="fupd" action="http://space.tp/mishaapi/upded" method="post">
	            			<input class="galaxyin" type="hidden" name="table" value="owner">
	            			<input class="galaxyin" type="hidden" name="id" value="<?=$data_g->id?>">
	            			<label>Название</label><br />
					    	<input class="galaxyin" type="text" placeholder="название" name="name" value="<?=$data_g->name?>" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s.]{0,29}" required><br />
					    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
					    </form><?
				    }
					else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "product") {
            		$out = $this->curvl_id("product", $this->request->getPost("id"));
            		if ($out->status == "Найдено" && count($out->data) != 0){
            			$data_g = $out->data;
	            		?>
	            		<form name="fupd" action="http://space.tp/mishaapi/upded" method="post">
	            			<input class="galaxyin" type="hidden" name="table" value="product">
	            			<input class="galaxyin" type="hidden" name="id" value="<?=$data_g->id?>">
	            			<label>Название</label><br />
					    	<input class="galaxyin" type="text" placeholder="название" name="name" value="<?=$data_g->name?>" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required><br />
					    	<label>Вес</label><br />
					    	<input class="galaxyin" type="number" placeholder="вес" name="weight" value="<?=$data_g->weight?>" title="Сюда ввдедите вес" pattern="[0-9]{1,11}" required><br />
					    	
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
				            			if ($data_g->type == $data->name) {
			            				?>
									    	<option selected="selected" value="<?=$data->id?>"><?=$data->name?></option>
									    <?
				            			}
				            			else
				            			{
										    ?>
										    	<option value="<?=$data->id?>"><?=$data->name?></option>
										    <?
										}
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
					else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "producttype") {
            		$out = $this->curvl_id("producttype", $this->request->getPost("id"));
            		if ($out->status == "Найдено" && count($out->data) != 0){
            			$data_g = $out->data;
	            		?>
	            		<form name="fupd" action="http://space.tp/mishaapi/upded" method="post">
	            			<input class="galaxyin" type="hidden" name="table" value="producttype">
	            			<input class="galaxyin" type="hidden" name="id" value="<?=$data_g->id?>">
	            			<label>Название</label><br />
					    	<input class="galaxyin" type="text" placeholder="название" name="name" value="<?=$data_g->name?>" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required>
					    	<br />
					    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
					    </form><?
				    }
					else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "shipment") {
            		$out = $this->curvl_id("shipment", $this->request->getPost("id"));
            		if ($out->status == "Найдено" && count($out->data) != 0){
            			$data_g = $out->data;
	            		?>
	            		<form name="fupd" action="http://space.tp/mishaapi/upded" method="post">
	            			<input class="galaxyin" type="hidden" name="table" value="shipment">
	            			<input class="galaxyin" type="hidden" name="id" value="<?=$data_g->id?>">
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
								    	if ($data_g->product == $data->name) {
				            				?>
										    	<option selected="selected" value="<?=$data->id?>"><?=$data->name?></option>
										    <?
				            			}
				            			else
				            			{
										    ?>
										    	<option value="<?=$data->id?>"><?=$data->name?></option>
										    <?
										}
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
								    	if ($data_g->transportation_number == $data->name) {
			            				?>
									    	<option selected="selected" value="<?=$data->id?>"><?=$data->id?></option>
									    <?
				            			}
				            			else
				            			{
										    ?>
										    	<option value="<?=$data->id?>"><?=$data->id?></option>
										    <?
										}
								    }
								}
								else
									break;
		            		}				    
						    ?>
						    </select>
						    <br />
						    <label>Кол-во</label><br />
					    	<input class="galaxyin" type="number" placeholder="кол-во" name="amount" value="<?=$data_g->amount?>" title="Сюда ввдедите кол-во" pattern="[0-9]{1,11}" required>
					    	<br />
					    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
					    </form>
					    <?
				    }
					else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "store") {
            		$out = $this->curvl_id("store", $this->request->getPost("id"));
            		if ($out->status == "Найдено" && count($out->data) != 0){
            			$data_g = $out->data;
	            		?>
	            		<form name="fupd" action="http://space.tp/mishaapi/upded" method="post">
	            			<input class="galaxyin" type="hidden" name="table" value="store">
	            			<input class="galaxyin" type="hidden" name="id" value="<?=$data_g->id?>">
	            			<label>Название</label><br />
					    	<input class="galaxyin" type="text" placeholder="название" name="name" value="<?=$data_g->name?>" title="Сюда ввдедите название" pattern="[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,29}" required>
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
								    	if ($data_g->owner == $data->name) {
			            				?>
									    	<option selected="selected" value="<?=$data->id?>"><?=$data->name?></option>
									    <?
				            			}
				            			else
				            			{
										    ?>
										    	<option value="<?=$data->id?>"><?=$data->name?></option>
										    <?
										}
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
					else
				    {
				    	echo "Ничего не найдено";
				    }
            	}
            	elseif ($this->request->getPost("table") == "transport") {
            		$out = $this->curvl_id("transport", $this->request->getPost("id"));
            		if ($out->status == "Найдено" && count($out->data) != 0){
            			$data_g = $out->data;
	            		?>
	            		<form name="fupd" action="http://space.tp/mishaapi/upded" method="post">
	            			<input class="galaxyin" type="hidden" name="table" value="transport">
	            			<input class="galaxyin" type="hidden" name="id" value="<?=$data_g->id?>">
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
								    	if ($data_g->car == $data->name) {
			            				?>
									    	<option selected="selected" value="<?=$data->id?>"><?=$data->model?></option>
									    <?
				            			}
				            			else
				            			{
										    ?>
										    	<option value="<?=$data->id?>"><?=$data->model?></option>
										    <?
										}
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
								    	if ($data_g->organization == $data->name) {
			            				?>
									    	<option selected="selected" value="<?=$data->id?>"><?=$data->name?></option>
									    <?
				            			}
				            			else
				            			{
										    ?>
										    	<option value="<?=$data->id?>"><?=$data->name?></option>
										    <?
										}
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
								    	if ($data_g->store == $data->name) {
			            				?>
									    	<option selected="selected" value="<?=$data->id?>"><?=$data->name?></option>
									    <?
				            			}
				            			else
				            			{
										    ?>
										    	<option value="<?=$data->id?>"><?=$data->name?></option>
										    <?
										}
								    }
								}
								else
									break;
		            		}				    
						    ?>
						    </select>
						    <br />
						    <label>Дата</label><br />
					    	<input class="galaxyin" type="date" placeholder="дата" name="date" value="<?=$data_g->date?>" title="Сюда ввдедите дату">
					    	<br />
					    	<input class="galaxybutton" name="add" type="submit" value="Добавить">
					    </form>
					    <?
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

    public function deleteAction()
    {
    	if ($this->request->isPost() == true) 
        {
        	$validation = new Validation();
        	$validation->add('table', new RegexValidator(array(
               'pattern' => '/[a-z\s]{1,30}/u',
               'message' => 'Выберите правильную таблицу<br />'
            )));
            $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,11}/u',
               'message' => 'ID параметра сдесь нет<br />'
            )));
            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {
            	if ($this->request->getPost("table") == "car") {
            		if ($this->curvl_del("car", $this->request->getPost("id"))->status == 'DELETED')
						$this->view->result = "Успешно";						
					else
					{
						$this->view->result = "Неудачно";
					}
		            		   
            	}
            	elseif ($this->request->getPost("table") == "dealer") {	
		            if ($this->curvl_del("dealer", $this->request->getPost("id"))->status == 'DELETED')
							$this->view->result = "Успешно";						
					else
					{
						$this->view->result = "Неудачно";
					}		            	
            	}
            	elseif ($this->request->getPost("table") == "driver") {
            		if ($this->curvl_del("driver", $this->request->getPost("id"))->status == 'DELETED')
							$this->view->result = "Успешно";						
					else
					{
						$this->view->result = "Неудачно";
					}		            		
            	}
            	elseif ($this->request->getPost("table") == "organization") {
            		if ($this->curvl_del("organization", $this->request->getPost("id"))->status == 'DELETED')
							$this->view->result = "Успешно";						
					else
					{
						$this->view->result = "Неудачно";
					}		            	
            	}
            	elseif ($this->request->getPost("table") == "owner") {
            		if ($this->curvl_del("owner", $this->request->getPost("id"))->status == 'DELETED')
							$this->view->result = "Успешно";						
					else
					{
						$this->view->result = "Неудачно";
					}		            	
            	}
            	elseif ($this->request->getPost("table") == "product") {
            		if ($this->curvl_del("product", $this->request->getPost("id"))->status == 'DELETED')
							$this->view->result = "Успешно";						
					else
					{
						$this->view->result = "Неудачно";
					}		            	
            	}
            	elseif ($this->request->getPost("table") == "producttype") {
            		if ($this->curvl_del("producttype", $this->request->getPost("id"))->status == 'DELETED')
							$this->view->result = "Успешно";						
					else
					{
						$this->view->result = "Неудачно";
					}		            	
            	}
            	elseif ($this->request->getPost("table") == "shipment") {
            		if ($this->curvl_del("shipment", $this->request->getPost("id"))->status == 'DELETED')
							$this->view->result = "Успешно";						
					else
					{
						$this->view->result = "Неудачно";
					}		            	
            	}
            	elseif ($this->request->getPost("table") == "store") {
            		if ($this->curvl_del("store", $this->request->getPost("id"))->status == 'DELETED')
							$this->view->result = "Успешно";						
					else
					{
						$this->view->result = "Неудачно";
					}		            	
            	}
            	elseif ($this->request->getPost("table") == "transport") {
            		if ($this->curvl_del("transport", $this->request->getPost("id"))->status == 'DELETED')
							$this->view->result = "Успешно";						
					else
					{
						$this->view->result = "Неудачно";
					}		            	
            	}
            	else {
            		echo "<p>Ой, что-то пошло не так</p>";
            	}
			    

            }
            else
            {
            	$this->view->messages = $messages;
                
            }
        }
        else
            $this->view->result = "Опаньки, поста то нет";
    }

}
?>