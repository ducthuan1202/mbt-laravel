<?php

namespace App\Helpers;


class Messages
{
    const
        UNKNOWN_TEXT = 'không xác định',
        DELETE_FAIL_BECAUSE_HAS_RELATIONSHIP_WITH = 'Không thể xóa do có liên quan tới';
    const
        INSERT_SUCCESS = 'Thêm mới thành công',
        UPDATE_SUCCESS = 'Cập nhật thành công',
        DELETE_SUCCESS = 'Xóa thành công';
    const
        INSERT_ERROR = 'Thêm mới thất bại',
        UPDATE_ERROR = 'Cập nhật thất bại',
        DELETE_ERROR = 'Xóa thất bại';
}
