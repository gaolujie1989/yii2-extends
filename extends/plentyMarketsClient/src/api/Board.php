<?php

namespace lujie\plentyMarkets\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The plentymarkets REST API expands the functionality of the plentymarkets CMS and allows access to resources, i.e. data records, via unique URI paths
*/
class Board extends \lujie\plentyMarkets\BasePlentyMarketsRestClient
{

                
    /**
     * @description Lists all columns of a given board. The ID of the board must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @return array
     */
    public function getBoardsColumnsByBoardId(string $boardId): array
    {
        return $this->api("/rest/boards/{$boardId}/columns");
    }
                
    /**
     * @description Creates a new column and assigns it to a given board. The ID of the board must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @param array $data 
     * @return array
     *      - *id* - string
     *          - The ID of the board column
     *      - *boardId* - string
     *          - The ID of the board
     *      - *columnName* - string
     *          - The name of the column
     *      - *position* - integer
     *          - The position
     */
    public function createBoardsColumnByBoardId(string $boardId, array $data): array
    {
        return $this->api("/rest/boards/{$boardId}/columns", 'POST', $data);
    }
                    
    /**
     * @description Deletes a specific column. The ID of the board and the ID of column must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @param string $columnId The ID of the column
     */
    public function deleteBoardsColumnByBoardIdColumnId(string $boardId, string $columnId)
    {
        return $this->api("/rest/boards/{$boardId}/columns/{$columnId}", 'DELETE');
    }
                
    /**
     * @description Copies a specific column. The ID of the board and the ID of the column must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @param string $columnId The ID of the column
     * @return array
     *      - *id* - string
     *          - The ID of the board column
     *      - *boardId* - string
     *          - The ID of the board
     *      - *columnName* - string
     *          - The name of the column
     *      - *position* - integer
     *          - The position
     */
    public function createBoardsColumnByBoardIdColumnId(string $boardId, string $columnId): array
    {
        return $this->api("/rest/boards/{$boardId}/columns/{$columnId}", 'POST');
    }
                
    /**
     * @description Updates a specific column. The ID of the board and the ID of the column must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @param string $columnId The ID of the column
     * @param array $data 
     * @return array
     *      - *id* - string
     *          - The ID of the board column
     *      - *boardId* - string
     *          - The ID of the board
     *      - *columnName* - string
     *          - The name of the column
     *      - *position* - integer
     *          - The position
     */
    public function updateBoardsColumnByBoardIdColumnId(string $boardId, string $columnId, array $data): array
    {
        return $this->api("/rest/boards/{$boardId}/columns/{$columnId}", 'PUT', $data);
    }
                    
    /**
     * @description Updates the position of a specific column. Also updates the positions of all following columns on the same board. The ID of the board and the ID of the column must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @param string $columnId The ID of the column
     * @param array $query
     *      - *position* - int - required
     *          - The position number
     */
    public function updateBoardsColumnsPositionByBoardIdColumnId(string $boardId, string $columnId, array $query)
    {
        return $this->api(array_merge(["/rest/boards/{$boardId}/columns/{$columnId}/position"], $query), 'PUT');
    }
                    
    /**
     * @description Lists all tasks of a given column. The ID of the board and the ID of the column must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @param string $columnId The ID of the column
     * @param array $query
     *      - *startAt* - int - optional
     *          - The position of a task to start listing at
     *      - *tasksPerPage* - int - optional
     *          - The number of tasks to list per page
     * @return array
     */
    public function getBoardsColumnsTasksByBoardIdColumnId(string $boardId, string $columnId, array $query = []): array
    {
        return $this->api(array_merge(["/rest/boards/{$boardId}/columns/{$columnId}/tasks"], $query));
    }
                
    /**
     * @description Creates a new task in a specific column. The ID of the board and the ID of the column must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @param string $columnId The ID of the column
     * @param array $data 
     * @return array
     *      - *id* - string
     *          - The ID of the board task
     *      - *taskName* - string
     *          - The name of the task
     *      - *description* - string
     *          - The description of the task
     *      - *position* - integer
     *          - The position of the task
     *      - *columnId* - string
     *          - The ID of the column
     *      - *boardId* - string
     *          - The ID of the board
     */
    public function createBoardsColumnsTaskByBoardIdColumnId(string $boardId, string $columnId, array $data): array
    {
        return $this->api("/rest/boards/{$boardId}/columns/{$columnId}/tasks", 'POST', $data);
    }
                    
    /**
     * @description Deletes a task. The ID of the board, the ID of the column and the ID of the task must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @param string $columnId The ID of the column
     * @param string $taskId The ID of the task
     */
    public function deleteBoardsColumnsTaskByBoardIdColumnIdTaskId(string $boardId, string $columnId, string $taskId)
    {
        return $this->api("/rest/boards/{$boardId}/columns/{$columnId}/tasks/{$taskId}", 'DELETE');
    }
                
    /**
     * @description Gets a task by its ID. The ID of the board, the ID of the column and the ID of the task must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @param string $columnId The ID of the column
     * @param string $taskId The ID of the task
     * @return array
     *      - *id* - string
     *          - The ID of the board task
     *      - *taskName* - string
     *          - The name of the task
     *      - *description* - string
     *          - The description of the task
     *      - *position* - integer
     *          - The position of the task
     *      - *columnId* - string
     *          - The ID of the column
     *      - *boardId* - string
     *          - The ID of the board
     */
    public function getBoardsColumnsTaskByBoardIdColumnIdTaskId(string $boardId, string $columnId, string $taskId): array
    {
        return $this->api("/rest/boards/{$boardId}/columns/{$columnId}/tasks/{$taskId}");
    }
                
    /**
     * @description Copies a specific task. The ID of the board, the ID of the column and the ID of the task must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @param string $columnId The ID of the column
     * @param string $taskId The ID of the task
     * @return array
     *      - *id* - string
     *          - The ID of the board task
     *      - *taskName* - string
     *          - The name of the task
     *      - *description* - string
     *          - The description of the task
     *      - *position* - integer
     *          - The position of the task
     *      - *columnId* - string
     *          - The ID of the column
     *      - *boardId* - string
     *          - The ID of the board
     */
    public function createBoardsColumnsTaskByBoardIdColumnIdTaskId(string $boardId, string $columnId, string $taskId): array
    {
        return $this->api("/rest/boards/{$boardId}/columns/{$columnId}/tasks/{$taskId}", 'POST');
    }
                
    /**
     * @description Updates a task. The ID of the board, the ID of the column and the ID of the task must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @param string $columnId The ID of the column
     * @param string $taskId The ID of the task
     * @param array $data 
     * @return array
     *      - *id* - string
     *          - The ID of the board task
     *      - *taskName* - string
     *          - The name of the task
     *      - *description* - string
     *          - The description of the task
     *      - *position* - integer
     *          - The position of the task
     *      - *columnId* - string
     *          - The ID of the column
     *      - *boardId* - string
     *          - The ID of the board
     */
    public function updateBoardsColumnsTaskByBoardIdColumnIdTaskId(string $boardId, string $columnId, string $taskId, array $data): array
    {
        return $this->api("/rest/boards/{$boardId}/columns/{$columnId}/tasks/{$taskId}", 'PUT', $data);
    }
                    
    /**
     * @description Updates the position of a task. The ID of the board, the ID of the column and the ID of the task must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @param string $columnId The ID of the column the task belongs to
     * @param string $taskId The ID of the task
     * @param array $query
     *      - *position* - int - required
     *          - The new position of the task
     */
    public function updateBoardsColumnsTasksPositionByBoardIdColumnIdTaskId(string $boardId, string $columnId, string $taskId, array $query)
    {
        return $this->api(array_merge(["/rest/boards/{$boardId}/columns/{$columnId}/tasks/{$taskId}/position"], $query), 'PUT');
    }
                    
    /**
     * @description Creates a new reference from a given task to a contact or a ticket. The ID of the board, the ID of the column and the ID of the task must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @param string $columnId The ID of the column
     * @param string $taskId The ID of the task
     * @param array $query
     *      - *referenceValue* - string - required
     *          - Reference type followed by foreign ID of the referenced object. Syntax: TYPE-ID Example: user-123456 Types: user,ticket,contact,order,item
     * @return array
     *      - *id* - string
     *          - The ID of the board task reference
     *      - *taskId* - string
     *          - The ID of the task to create a reference for
     *      - *referenceValue* - string
     *          - Reference type followed by foreign ID of the referenced object. Syntax: TYPE-ID Example: user-123456 Types: user,ticket,contact,order,item
     */
    public function createBoardsColumnsTasksReferenceByBoardIdColumnIdTaskId(string $boardId, string $columnId, string $taskId, array $query): array
    {
        return $this->api(array_merge(["/rest/boards/{$boardId}/columns/{$columnId}/tasks/{$taskId}/references"], $query), 'POST');
    }
                    
    /**
     * @description Deletes a reference from a given task. The ID of the board, the ID of the column, the ID of the task and the ID of the reference must be specified.
     * @tag Board
     * @param string $boardId The ID of the task
     * @param string $columnId The ID of the column
     * @param string $taskId The ID of the task
     * @param string $referenceId The ID of the reference
     */
    public function deleteBoardsColumnsTasksReferenceByBoardIdColumnIdTaskIdReferenceId(string $boardId, string $columnId, string $taskId, string $referenceId)
    {
        return $this->api("/rest/boards/{$boardId}/columns/{$columnId}/tasks/{$taskId}/references/{$referenceId}", 'DELETE');
    }
                    
    /**
     * @description Checks the reference key. The ID of the board, the ID of the column and the ID of the task as well as the reference type and the reference key must be specified.
     * @tag Board
     * @param string $boardId The ID of the board
     * @param string $columnId The ID of the column
     * @param string $taskId The ID of the task
     * @param string $referenceType The type of the reference
     * @param int $referenceKey The key of the reference
     */
    public function getBoardsColumnsTasksReferenceByBoardIdColumnIdTaskIdReferenceTypeReferenceKey(string $boardId, string $columnId, string $taskId, string $referenceType, int $referenceKey)
    {
        return $this->api("/rest/boards/{$boardId}/columns/{$columnId}/tasks/{$taskId}/references/{$referenceType}/{$referenceKey}");
    }
    
}