<?
/** @noinspection ALL */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Application;
use QSOFT\ORM\CategoryTable;
use Bitrix\Main\Grid\Options;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;

Loc::loadLanguageFile(__FILE__);

/**
 * Class CategoryManager
 *
 * Класс работы с категориями. Позволяет редактировать, удалять, изменять, добавлять и выгружать категории.
 *
 */
class CategoryManager extends CBitrixComponent
{

    /**
     * Переменная для запроса данных из базы.
     * @var
     */
    private $categoryQuery;
    /**
     *  Переменная для формирования навигации.
     * @var
     */
    private $categoryNavigation;
    /**
     * Массив данных.
     * @var array
     */
    private $categoryList;
    /**
     * Наименования таблицы в базе данных откуда происходит выборка.
     * @var string
     */
    private $listId;
    /**
     * Перменная с параметрами для выбранной таблицы.
     * @var mixed
     */
    private $options;
    /**
     * Переменная для указания сортировки при выборке из базы данных
     * @var array
     */
    private $sort;

    /**
     * Метод получает категории и выводит их на стороне клиента. Приватный метод
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @return mixed
     */
    private function getCategory()
    {
        $this->listId = "category_table";

        $this->categoryNavigation = new PageNavigation($this->listId);
        $this->categoryNavigation->allowAllRecords(true)->setPageSize($this->arParams["ELEMENTS_COUNT"])->initFromUri();

        $this->options = new Options($this->listId);
        $this->sort = $this->options->GetSorting(["sort" => ["ID" => "DESC"]]);

        $this->categoryQuery = CategoryTable::getList(
            array(
             "select" => array("ID", "NAME"),
             "filter" => array("ACTIVE" => "Y"),
             "order" => $this->sort["sort"],
             "count_total" => true,
             "offset" => $this->categoryNavigation->getOffset(),
             "limit" => $this->categoryNavigation->getLimit(),
            )
        );

        while ($cat = $this->categoryQuery->Fetch())
        {
            $cols = [
                "ID" => $cat["ID"],
                "NAME" => trim($cat["NAME"]),
            ];

            $this->categoryList[] = array(
                "data" => $cols,
                'actions' => array(
                    array(
                        'text' => Loc::getMessage('EDIT'),
                        'onclick' => "dataRename(".$cols["ID"].", '".htmlspecialchars($cols["NAME"])."')"
                    ),
                    array(
                        'text' => Loc::getMessage('DELETE'),
                        'onclick' => 'Delete('.$cols["ID"].')'
                    ),
                ),
                'editable' => true
            );
        }

        $this->categoryNavigation->setRecordCount($this->categoryQuery->getCount());

        return true;
    }

    /**
     * Метод позволяет переименовать выбранную категорию. Приватный метод
     * @param integer $id id выбранной категории
     * @param string $name новое имя
     * @throws Exception
     * @return \Bitrix\Main\Entity\UpdateResult
     */
    private function renameCategory($id, $name)
    {
        $id = intval($id);
        $name = htmlspecialchars($name);
        return CategoryTable::update($id, ["NAME" => $name]);
    }

    /**
     * Метод удаляет категорию из базы данных. Приватный метод
     * @param integer $id id выбранной категории для удаления
     * @throws Exception
     * @return \Bitrix\Main\Entity\DeleteResult
     */
    private function deleteCategory($id)
    {
        $id = intval($id);
        return CategoryTable::delete($id);
    }

    /**
     * Метод добавляет категорию в базу данных. Приватный метод
     * @param string $name название новой категории
     * @throws Exception
     * @return \Bitrix\Main\Entity\AddResult
     */
    private function addCategory($name)
    {
        $name = htmlspecialchars($name);
        return CategoryTable::add(["NAME" => $name, "ACTIVE" => "Y"]);
    }

    /**
     * Метод выгружает данные в csv файл на пользовательское устройство. Приватный метод
     * @throws \Bitrix\Main\ArgumentException
     * @return mixed
     */
    private function exportCsv()
    {
        $GLOBALS['APPLICATION']->RestartBuffer();

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=category.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $dataItems = [];

        $dataQuery = CategoryTable::getList(
            array(
                "select" => array("ID", "NAME"),
                "filter" => array("ACTIVE" => "Y"),
                "order" => ["ID" => "ASC"],
            )
        );

        while ($cat = $dataQuery->Fetch())
        {
            $dataItems[] = [
                "ID" =>  trim($cat["ID"]),
                "NAME" => trim($cat["NAME"]),
            ];
        }

        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($out, array("ID", "NAME"), ";");

        foreach ($dataItems as $item) {
            fputcsv($out, array($item["ID"],$item["NAME"]), ";");
        }

        fclose($out);

        die();
    }

    public function executeComponent()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        if (!empty($request->getPost("action")))
        {
            switch ($request->getPost("action"))
            {
                case "delete":
                    $this->deleteCategory($request->getPost("id"));
                    break;
                case "rename":
                    $this->renameCategory($request->getPost("id"), $request->getPost("name"));
                    break;
                case "add":
                    $this->addCategory($request->getPost("name"));
                    break;
                case "export":
                    $this->exportCsv();
                    break;
            }
        }

        $this->getCategory();
        $this->arResult["CATEGORY"] = $this->categoryList;
        $this->arResult["NAV"] = $this->categoryNavigation;
        $this->arResult["LIST_ID"] = $this->listId;
        $this->includeComponentTemplate();
    }

}
