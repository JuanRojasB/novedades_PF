<!-- Modal: Crear Sede -->
<div id="modal-crear-sede" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Agregar Sede</h3>
            <button class="modal-close" onclick="closeModal('modal-crear-sede')">&times;</button>
        </div>
        <form action="<?php echo base_url('admin/crearSede'); ?>" method="POST">
            <div class="modal-body">
                <div class="form-field">
                    <label class="required">Nombre de la Sede</label>
                    <input type="text" name="nombre" required placeholder="Ej: Sede 4">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-crear-sede')">Cancelar</button>
                <button type="submit" class="btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Editar Sede -->
<div id="modal-editar-sede" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Sede</h3>
            <button class="modal-close" onclick="closeModal('modal-editar-sede')">&times;</button>
        </div>
        <form action="<?php echo base_url('admin/actualizarSede'); ?>" method="POST">
            <input type="hidden" name="id" id="edit-sede-id">
            <div class="modal-body">
                <div class="form-field">
                    <label class="required">Nombre de la Sede</label>
                    <input type="text" name="nombre" id="edit-sede-nombre" required>
                </div>
                <div class="form-field">
                    <label class="checkbox-label">
                        <input type="checkbox" name="activo" id="edit-sede-activo">
                        <span>Activo</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-editar-sede')">Cancelar</button>
                <button type="submit" class="btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Crear Área -->
<div id="modal-crear-area" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Agregar Área de Trabajo</h3>
            <button class="modal-close" onclick="closeModal('modal-crear-area')">&times;</button>
        </div>
        <form action="<?php echo base_url('admin/crearArea'); ?>" method="POST">
            <div class="modal-body">
                <div class="form-field">
                    <label class="required">Nombre del Área</label>
                    <input type="text" name="nombre" required placeholder="Ej: RECURSOS HUMANOS">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-crear-area')">Cancelar</button>
                <button type="submit" class="btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Editar Área -->
<div id="modal-editar-area" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Área de Trabajo</h3>
            <button class="modal-close" onclick="closeModal('modal-editar-area')">&times;</button>
        </div>
        <form action="<?php echo base_url('admin/actualizarArea'); ?>" method="POST">
            <input type="hidden" name="id" id="edit-area-id">
            <div class="modal-body">
                <div class="form-field">
                    <label class="required">Nombre del Área</label>
                    <input type="text" name="nombre" id="edit-area-nombre" required>
                </div>
                <div class="form-field">
                    <label class="checkbox-label">
                        <input type="checkbox" name="activo" id="edit-area-activo">
                        <span>Activo</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-editar-area')">Cancelar</button>
                <button type="submit" class="btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Crear Tipo de Novedad -->
<div id="modal-crear-tipo" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Agregar Tipo de Novedad</h3>
            <button class="modal-close" onclick="closeModal('modal-crear-tipo')">&times;</button>
        </div>
        <form action="<?php echo base_url('admin/crearTipoNovedad'); ?>" method="POST">
            <div class="modal-body">
                <div class="form-field">
                    <label class="required">Nombre del Tipo</label>
                    <input type="text" name="nombre" required placeholder="Ej: LICENCIA MÉDICA">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-crear-tipo')">Cancelar</button>
                <button type="submit" class="btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Editar Tipo de Novedad -->
<div id="modal-editar-tipo" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Tipo de Novedad</h3>
            <button class="modal-close" onclick="closeModal('modal-editar-tipo')">&times;</button>
        </div>
        <form action="<?php echo base_url('admin/actualizarTipoNovedad'); ?>" method="POST">
            <input type="hidden" name="id" id="edit-tipo-id">
            <div class="modal-body">
                <div class="form-field">
                    <label class="required">Nombre del Tipo</label>
                    <input type="text" name="nombre" id="edit-tipo-nombre" required>
                </div>
                <div class="form-field">
                    <label class="checkbox-label">
                        <input type="checkbox" name="activo" id="edit-tipo-activo">
                        <span>Activo</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-editar-tipo')">Cancelar</button>
                <button type="submit" class="btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>
