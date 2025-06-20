import React from 'react';
import './WidgetForm.css';

function WidgetForm({ widgets }) {
  return (
    <form className="form" method="POST" name="submitZapTool" style={{ backgroundColor: '#af52de' }}>
      <table className="table" cellPadding="0" cellSpacing="0" width="100%">
        <thead>
          <tr className="nodrag nodrop">
            <th>Language</th>
            <th>Script</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {widgets.map((widget, index) => (
            <tr key={index} className={index % 2 === 0 ? 'alt_row' : undefined}>
              <td>{widget.lang}</td>
              <td>
                <textarea
                  rows="6"
                  cols="100"
                  name={`zaptool[${widget.id_lang_zaptool}]`}
                  defaultValue={widget.script}
                />
              </td>
              <td>
                <span>
                  <img className="help" src="../img/admin/help2.png" alt="Info" />
                  <img className="dashboard" src="../modules/zaptool/views/img/dashboard.png" alt="Info" />
                </span>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      <p>
        <input type="submit" name="submitZapTool" className="button" />
      </p>
    </form>
  );
}

export default WidgetForm;
