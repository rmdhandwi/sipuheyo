<?php

test('Get_Obat_Expect_Empty', function () {
    $response = $this->get('/obat');
    $response->assertStatus(200);
});
