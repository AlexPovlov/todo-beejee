export default function WarnDate({children}) {
  
  return (
    <div className='col-auto m-1 p-0 px-3'>
      <div className='row'>
        <div className='col-auto d-flex align-items-center rounded bg-white border border-warning'>
          <i
            className='fa fa-hourglass-2 my-2 px-2 text-warning btn'
            data-toggle='tooltip'
            data-placement='bottom'
            title=''
            data-original-title='Due on date'
          ></i>
          <h6 className='text my-2 pr-2'>{children}</h6>
        </div>
      </div>
    </div>
  );
}
